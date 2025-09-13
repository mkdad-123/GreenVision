<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\FinancialRecord;

class FinancialRecordSeeder extends Seeder
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

        // مثال 1: دخل - دعم زراعي
        FinancialRecord::create([
            'user_id'          => $userId,
            'direction'        => 'دخل',
            'category'         => 'دعم زراعي',
            'amount'           => 1500,
            'date'             => Carbon::create(2025, 9, 5)->toDateString(),
            'description'      => 'منحة دعم حكومية للموسم الزراعي.',
            'reference_number' => 'SUP-2025-001',
        ]);

        // مثال 2: نفقات - وقود
        FinancialRecord::create([
            'user_id'          => $userId,
            'direction'        => 'نفقات',
            'category'         => 'وقود',
            'amount'           => 600,
            'date'             => Carbon::create(2025, 9, 12)->toDateString(),
            'description'      => 'شراء ديزل لتشغيل مضخات الري.',
            'reference_number' => 'FUEL-2025-078',
        ]);

        // مثال 3: نفقات - عمالة
        FinancialRecord::create([
            'user_id'          => $userId,
            'direction'        => 'نفقات',
            'category'         => 'عمالة',
            'amount'           => 900,
            'date'             => Carbon::create(2025, 9, 20)->toDateString(),
            'description'      => 'دفع أجور عمال الحصاد.',
            'reference_number' => 'LAB-2025-220',
        ]);
    }
}
