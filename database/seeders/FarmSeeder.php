<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Farm;
use Illuminate\Support\Facades\Hash;

class FarmSeeder extends Seeder
{
    public function run(): void
    {
        // تأكد في Users IDs فعليًا
        $userIds = User::pluck('id')->all();

        // إذا ما في مستخدمين، أنشئ 3 افتراضيين
        if (count($userIds) < 3) {
            $defaults = [
                ['name' => 'User One',   'email' => 'user1@example.com'],
                ['name' => 'User Two',   'email' => 'user2@example.com'],
                ['name' => 'User Three', 'email' => 'user3@example.com'],
            ];
            foreach ($defaults as $u) {
                $user = User::firstOrCreate(
                    ['email' => $u['email']],
                    ['name' => $u['name'], 'password' => Hash::make('12345678')]
                );
            }
            $userIds = User::pluck('id')->all(); // حدّث القائمة
        }

        // وزّع المزارع على IDs المتاحة (Round-Robin)
        $pick = fn($i) => $userIds[$i % count($userIds)];

        Farm::create([
            'user_id'        => $pick(0),
            'name'           => 'مزرعة السهل الأخضر',
            'location'       => 'ريف دمشق',
            'crop_type'      => 'قمح',
            'soil_type'      => 'طينية',
            'area'           => 25.5,
            'notes'          => 'تحتاج متابعة ري أسبوعية.',
            'irrigation_type'=> 'ري بالرش',
            'status'         => 'نشطة',
        ]);

        Farm::create([
            'user_id'        => $pick(1),
            'name'           => 'بستان الزيتون',
            'location'       => 'حماة',
            'crop_type'      => 'زيتون',
            'soil_type'      => 'طميية',
            'area'           => 40,
            'notes'          => 'أشجار معمّرة بعمر 20 سنة.',
            'irrigation_type'=> 'ري بالتنقيط',
            'status'         => 'نشطة',
        ]);

        Farm::create([
            'user_id'        => $pick(2),
            'name'           => 'مزرعة العنب',
            'location'       => 'حمص',
            'crop_type'      => 'عنب أبيض',
            'soil_type'      => 'رملية',
            'area'           => 15,
            'notes'          => 'مزرعة صغيرة للتجارب.',
            'irrigation_type'=> 'يدوي',
            'status'         => 'غير نشطة',
        ]);

        Farm::create([
            'user_id'        => $pick(3),
            'name'           => 'مزرعة الخضار',
            'location'       => 'درعا',
            'crop_type'      => 'خيار وبندورة',
            'soil_type'      => 'طينية رملية',
            'area'           => 10,
            'notes'          => 'إنتاج تجاري للتصدير.',
            'irrigation_type'=> 'ري بالأنابيب',
            'status'         => 'نشطة',
        ]);

        Farm::create([
            'user_id'        => $pick(4),
            'name'           => 'بستان التفاح',
            'location'       => 'السويداء',
            'crop_type'      => 'تفاح أحمر',
            'soil_type'      => 'طينية خصبة',
            'area'           => 30,
            'notes'          => 'يحتاج لتقليم سنوي منتظم.',
            'irrigation_type'=> 'ري بالرش',
            'status'         => 'نشطة',
        ]);
    }
}
