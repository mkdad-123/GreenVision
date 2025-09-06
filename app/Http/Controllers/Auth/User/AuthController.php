<?php

namespace App\Http\Controllers\Auth\User;


use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Admin;
use App\Services\User\Auth\LoginService;
use App\Services\User\Auth\RegisterService;
use App\Traits\ResetPasswordTrait;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ResetPasswordTrait;

    public function showLoginForm()
    {
        // أعرض صفحة الـBlade (الكود بالأسفل)
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register'); // سننشئه بالأسفل
    }

   public function register(RegisterRequest $request)
    {
        // استدعاء الخدمة لإنشاء المستخدم فقط (بدون توكن)
        $result = (new RegisterService())->register($request);

        if ($result->status !== 201 && $result->status !== 200) {
            // في حال الخدمة أعادت فشل، أرجع للأمام مع الأخطاء
            return back()->withInput()->withErrors([
                'register' => $result->message ?? 'تعذّر إنشاء الحساب',
            ]);
        }

        // الحصول على المستخدم المُنشأ من $result->data['user'] مثلاً
        $user = $result->data['user'] ?? null;

        if (! $user) {
            return back()->withInput()->withErrors([
                'register' => 'تم إنشاء الحساب لكن لم نستطع تسجيل دخولك تلقائياً',
            ]);
        }

        // تسجيل الدخول بالجلسة + حماية من Session Fixation
        Auth::guard('user')->login($user);
        $request->session()->regenerate();

        // توجيه للصفحة الرئيسية مع فلاش مسج
        return redirect()->intended(route('home'))->with('status', 'تم إنشاء الحساب بنجاح! أهلاً بك 👋');
    }


    public function login(LoginRequest $request)
    {
        // منطق الدخول انتقل إلى الـService، لكن من دون JSON
        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (! auth('user')->attempt($credentials, $remember)) {
            // فشل: رجّع إلى الخلف مع رسالة خطأ
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'بيانات الدخول غير صحيحة']);
        }

        $request->session()->regenerate();

        // نجاح: وجّه إلى المقصد المقصود أو /home
        return redirect()->intended(route('home'));
    }


   public function logout(Request $request)
    {
        auth('user')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function me()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json(['user' => $user], 200);
    }

}
