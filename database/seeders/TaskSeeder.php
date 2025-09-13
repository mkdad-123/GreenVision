<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Farm;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        // تأكد من وجود مستخدم واحد على الأقل
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Default User',
                'email' => 'default@example.com',
                'password' => Hash::make('12345678'),
            ]);
        }

        // تأكد من وجود مزرعة واحدة على الأقل تابعة له
        $farm = Farm::first();
        if (!$farm) {
            $farm = Farm::create([
                'user_id'        => $user->id,
                'name'           => 'مزرعة افتراضية',
                'location'       => 'ريف دمشق',
                'crop_type'      => 'بندورة',
                'soil_type'      => 'طينية',
                'area'           => 5,
                'notes'          => 'إنشاء تلقائي لغايات الربط.',
                'irrigation_type'=> 'ري بالتنقيط',
                'status'         => 'نشطة',
            ]);
        }

        // IDs جاهزة
        $userId = $user->id;
        $farmId = $farm->id;

        // 1) مهمة ري
        Task::create([
            'user_id'         => $userId,
            'farm_id'         => $farmId,
            'type'            => 'ري',
            'description'     => 'تشغيل نظام التنقيط لمدة 45 دقيقة للبيت البلاستيكي الأول.',
            'date'            => Carbon::today()->toDateString(),
            'repeat_interval' => 'يومي',
            'status'          => 'قيد التنفيذ',
            'priority'        => 'عالية',
        ]);

        // 2) مهمة تسميد
        Task::create([
            'user_id'         => $userId,
            'farm_id'         => $farmId,
            'type'            => 'تسميد',
            'description'     => 'إضافة NPK بنسبة 20-20-20 بجرعة 2 كغ/دونم.',
            'date'            => Carbon::today()->addDays(2)->toDateString(),
            'repeat_interval' => 'اسبوعي',
            'status'          => 'مؤجلة',
            'priority'        => 'متوسطة',
        ]);

        // 3) مهمة رش وقائي
        Task::create([
            'user_id'         => $userId,
            'farm_id'         => $farmId,
            'type'            => 'رش وقائي',
            'description'     => 'رش مانكوزيب بتركيز 2 غ/لتر لوقاية الأوراق بعد المطر.',
            'date'            => Carbon::today()->addDays(4)->toDateString(),
            'repeat_interval' => 'ثلاث ايام',
            'status'          => 'قيد التنفيذ',
            'priority'        => 'عالية',
        ]);

        // 4) مهمة تنظيف/صيانة
        Task::create([
            'user_id'         => $userId,
            'farm_id'         => $farmId,
            'type'            => 'صيانة',
            'description'     => 'تنظيف الفلاتر وفحص ضغط شبكة الري.',
            'date'            => Carbon::today()->addWeek()->toDateString(),
            'repeat_interval' => 'شهري',
            'status'          => 'منجزة',
            'priority'        => 'منخفضة',
        ]);

        // 5) متابعة حصاد
        Task::create([
            'user_id'         => $userId,
            'farm_id'         => $farmId,
            'type'            => 'حصاد',
            'description'     => 'متابعة حصاد الدفعة الأولى وفرز الثمار للتسويق.',
            'date'            => Carbon::today()->addDays(10)->toDateString(),
            'repeat_interval' => 'اسبوعي',
            'status'          => 'قيد التنفيذ',
            'priority'        => 'متوسطة',
        ]);
    }
}
