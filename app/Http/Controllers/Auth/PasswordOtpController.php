<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordOtp;
use App\Notifications\OtpResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class PasswordOtpController extends Controller
{
    // 4.1 إرسال OTP إلى البريد
    public function send(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required','email'],
        ]);

        // Rate limit حسب الإيميل + IP (5 محاولات في 10 دقائق)
        $key = 'otp_send:'.sha1($request->ip().'|'.$validated['email']);
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => ["الرجاء المحاولة لاحقًا بعد {$seconds} ثانية."],
            ]);
        }
        RateLimiter::hit($key, 600); // 10 دقائق

        $user = User::where('email', $validated['email'])->first();

        // لأسباب أمان: نفس الرسالة حتى لو البريد غير موجود
        if (!$user) {
            return response()->json([
                'message' => 'تم إرسال الرمز إن كان البريد موجودًا لدينا.',
            ]);
        }

        // احذف أكواد قديمة غير مستخدمة لنفس البريد (تنظيف)
        PasswordOtp::where('email', $user->email)
            ->whereNull('used_at')
            ->delete();

        // توليد كود 6 أرقام
        $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // حفظه مُشفّرًا
        $otp = PasswordOtp::create([
            'email'      => $user->email,
            'code_hash'  => Hash::make($code),
            'expires_at' => Carbon::now()->addMinutes(10),
            'ip'         => $request->ip(),
            'user_agent' => substr((string)$request->userAgent(), 0, 255),
        ]);

        // إرسال الإيميل
        $user->notify(new OtpResetPassword($code));

        return response()->json([
            'message' => 'يرجى التحقق من بريدك الالكتروني ',
            // لتجارب التطوير فقط — لا تُرجعه في الإنتاج
            // 'debug_code' => $code,
        ]);
    }

    // 4.2 إعادة تعيين مباشرة باستخدام (email + code + password)
    public function reset(Request $request)
    {
        $validated = $request->validate([
            'email'                 => ['required','email'],
            'code'                  => ['required','digits:6'],
            'password'              => ['required','string','min:8','confirmed'],
        ]);

        $user = User::where('email', $validated['email'])->first();
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['بيانات غير صحيحة.'],
            ]);
        }

        $otp = PasswordOtp::where('email', $user->email)
            ->whereNull('used_at')
            ->latest('id')
            ->first();

        if (!$otp) {
            throw ValidationException::withMessages([
                'code' => ['لا يوجد رمز صالح، أعد الإرسال.'],
            ]);
        }

        if (now()->greaterThan($otp->expires_at)) {
            throw ValidationException::withMessages([
                'code' => ['انتهت صلاحية الرمز، أعد الإرسال.'],
            ]);
        }

        if ($otp->attempts >= 5) {
            throw ValidationException::withMessages([
                'code' => ['تم تجاوز عدد المحاولات، أعد إرسال رمز جديد.'],
            ]);
        }

        $isValid = Hash::check($validated['code'], $otp->code_hash);

        $otp->increment('attempts');

        if (!$isValid) {
            throw ValidationException::withMessages([
                'code' => ['الرمز غير صحيح.'],
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        $otp->update(['used_at' => now()]);


        return response()->json([
            'message' => 'تم تغيير كلمة المرور بنجاح.',
        ]);
    }

    public function resend(Request $request)
    {
        return $this->send($request);
    }


      public function showForm(Request $request)
    {
        $email = $request->query('email');

        return view('auth.passwords.otp_reset', [
            'email' => $email,
        ]);
    }

    public function resetFromBlade(Request $request)
    {
        $validated = $request->validate([
            'email'                 => ['required','email'],
            'code'                  => ['required','digits:6'],
            'password'              => ['required','string','min:8','confirmed'],
        ]);

        $user = User::where('email', $validated['email'])->first();
        if (!$user) {
            return back()->withErrors(['email' => 'بيانات غير صحيحة.'])->withInput();
        }

        $otp = PasswordOtp::where('email', $user->email)
            ->whereNull('used_at')
            ->latest('id')
            ->first();

        if (!$otp) {
            return back()->withErrors(['code' => 'لا يوجد رمز صالح، أعد الإرسال.'])->withInput();
        }

        if (now()->greaterThan($otp->expires_at)) {
            return back()->withErrors(['code' => 'انتهت صلاحية الرمز، أعد الإرسال.'])->withInput();
        }

        if ($otp->attempts >= 5) {
            return back()->withErrors(['code' => 'تجاوزت عدد المحاولات، أرسل رمزًا جديدًا.'])->withInput();
        }

        $otp->increment('attempts');

        if (!Hash::check($validated['code'], $otp->code_hash)) {
            return back()->withErrors(['code' => 'الرمز غير صحيح.'])->withInput();
        }

        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        $otp->update(['used_at' => now()]);


return redirect()
    ->route('password.otp.form', ['email' => $user->email])
    ->with('status', 'تم تغيير كلمة المرور بنجاح. يمكنك استخدام كلمة المرور الجديدة الآن.');    }
}
