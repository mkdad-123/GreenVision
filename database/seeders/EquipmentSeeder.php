<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Equipment;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        // تأكد من وجود مستخدم واحد على الأقل
        $user = User::first() ?? User::create([
            'name'     => 'Default User',
            'email'    => 'default@example.com',
            'password' => Hash::make('12345678'),
        ]);

        $userId = $user->id;

        // 1) مضخة ري ديزل
        Equipment::create([
            'user_id'          => $userId,
            'name'             => 'مضخة ري ديزل 3 إنش',
            'serial_number'    => 'PMP-3D-2025-001',
            'purchase_date'    => Carbon::today()->subMonths(8)->toDateString(),
            'last_maintenance' => Carbon::today()->subMonths(1)->toDateString(),
            'next_maintenance' => Carbon::today()->addMonths(2)->toDateString(),
            'status'           => 'نشطة',
            'type'             => 'مضخة',
            'location'         => 'غرفة المضخات - الجهة الشرقية',
            'usage_hours'      => '8', // ضمن enum
            'notes'            => 'تبديل فلاتر وزيت بالزيارة القادمة.',
        ]);

        // 2) جرار زراعي صغير
        Equipment::create([
            'user_id'          => $userId,
            'name'             => 'جرار زراعي 45 حصان',
            'serial_number'    => 'TR-45HP-19A-7782',
            'purchase_date'    => Carbon::today()->subYears(2)->toDateString(),
            'last_maintenance' => Carbon::today()->subWeeks(2)->toDateString(),
            'next_maintenance' => Carbon::today()->addWeeks(10)->toDateString(),
            'status'           => 'نشطة',
            'type'             => 'جرار',
            'location'         => 'هنغار المعدات',
            'usage_hours'      => '24',
            'notes'            => 'تم فحص ضغط الإطارات واستبدال البطارية.',
        ]);

        // 3) مرش مبيدات محمول
        Equipment::create([
            'user_id'          => $userId,
            'name'             => 'مرش مبيدات ظهرّي 20 لتر',
            'serial_number'    => 'SPR-20L-MAN-5521',
            'purchase_date'    => Carbon::today()->subMonths(3)->toDateString(),
            'last_maintenance' => Carbon::today()->subDays(10)->toDateString(),
            'next_maintenance' => Carbon::today()->addMonths(1)->toDateString(),
            'status'           => 'تحت الصيانة',
            'type'             => 'مرش',
            'location'         => 'ورشة الصيانة',
            'usage_hours'      => '1.5',
            'notes'            => 'تسريب بسيط في الهوز قيد الإصلاح.',
        ]);

        // 4) نظام تنقيط خط رئيسي
        Equipment::create([
            'user_id'          => $userId,
            'name'             => 'شبكة ري بالتنقيط - خط رئيسي',
            'serial_number'    => 'DRIP-MAIN-2024-ALPHA',
            'purchase_date'    => Carbon::today()->subYear()->toDateString(),
            'last_maintenance' => Carbon::today()->subMonths(2)->toDateString(),
            'next_maintenance' => Carbon::today()->addMonths(4)->toDateString(),
            'status'           => 'نشطة',
            'type'             => 'شبكة ري',
            'location'         => 'القطاع الشمالي',
            'usage_hours'      => '5',
            'notes'            => 'تحتاج تنظيف فلاتر كل أسبوعين.',
        ]);

        // 5) موتور كهربائي معطّل
        Equipment::create([
            'user_id'          => $userId,
            'name'             => 'موتور كهربائي 7.5 كيلوواط',
            'serial_number'    => 'EM-7K5-4040X',
            'purchase_date'    => Carbon::today()->subYears(3)->toDateString(),
            'last_maintenance' => Carbon::today()->subMonths(7)->toDateString(),
            'next_maintenance' => Carbon::today()->addMonths(1)->toDateString(),
            'status'           => 'معطلة',
            'type'             => 'محرك',
            'location'         => 'مخزن قطع الغيار',
            'usage_hours'      => '0.5',
            'notes'            => 'قصر في اللفات، بانتظار إعادة لف.',
        ]);
    }
}
