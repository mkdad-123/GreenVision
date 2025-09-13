<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Inventory;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        // تأكد من وجود مستخدم واحد على الأقل
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name'     => 'Default User',
                'email'    => 'default@example.com',
                'password' => Hash::make('12345678'),
            ]);
        }
        $userId = $user->id;

        // 1) سماد NPK
        Inventory::create([
            'user_id'          => $userId,
            'name'             => 'سماد NPK 20-20-20',
            'type'             => 'سماد',
            'quantity'         => 50,     // 50 كغ
            'unit'             => 'كغ',
            'purchase_date'    => Carbon::today()->subDays(7)->toDateString(),
            'expiry_date'      => Carbon::today()->addMonths(18)->toDateString(),
            'min_threshold'    => 10,
            'supplier'         => 'شركة المحاصيل الخضراء',
            'storage_location' => 'المخزن الرئيسي',
            'notes'            => 'للاستخدام الورقي والري بالتنقيط.',
        ]);

        // 2) مبيد فطري مانكوزيب
        Inventory::create([
            'user_id'          => $userId,
            'name'             => 'مانكوزيب 80%',
            'type'             => 'مبيد',
            'quantity'         => 12,     // 12 كغ
            'unit'             => 'كغ',
            'purchase_date'    => Carbon::today()->subDays(20)->toDateString(),
            'expiry_date'      => Carbon::today()->addYears(2)->toDateString(),
            'min_threshold'    => 3,
            'supplier'         => 'كيما الزراعية',
            'storage_location' => 'مخزن 1 - رف المبيدات',
            'notes'            => 'يذاب بمعدل 2 غ/لتر للرش الوقائي.',
        ]);

        // 3) بذور بندورة صنف هجين
        Inventory::create([
            'user_id'          => $userId,
            'name'             => 'بذور بندورة هجين F1',
            'type'             => 'بذور',
            'quantity'         => 500,    // 500 جرام
            'unit'             => 'جرام',
            'purchase_date'    => Carbon::today()->toDateString(),
            'expiry_date'      => Carbon::today()->addMonths(24)->toDateString(),
            'min_threshold'    => 100,
            'supplier'         => 'بذور الشرق',
            'storage_location' => 'قبو التبريد',
            'notes'            => 'حيوية عالية، إنبات ممتاز.',
        ]);

        // 4) زيت معدني صيفي (سائل)
        Inventory::create([
            'user_id'          => $userId,
            'name'             => 'زيت معدني صيفي',
            'type'             => 'مبيد',
            'quantity'         => 20,     // 20 لتر
            'unit'             => 'لتر',
            'purchase_date'    => Carbon::today()->subDays(3)->toDateString(),
            'expiry_date'      => Carbon::today()->addMonths(12)->toDateString(),
            'min_threshold'    => 5,
            'supplier'         => 'البيت الزراعي',
            'storage_location' => 'مخزن 2 - رف السوائل',
            'notes'            => 'يرش صباحًا لتقليل الأذى على الأوراق.',
        ]);

        // 5) أكياس تعبئة (مستلزمات)
        Inventory::create([
            'user_id'          => $userId,
            'name'             => 'أكياس تعبئة سعة 25 كغ',
            'type'             => 'مستلزمات',
            'quantity'         => 200,     // 200 كيس
            'unit'             => 'كيس',
            'purchase_date'    => Carbon::today()->subMonth()->toDateString(),
            'expiry_date'      => null,    // لا تنتهي
            'min_threshold'    => 50,
            'supplier'         => 'مصنع الشرق للأكياس',
            'storage_location' => 'المستودع الجانبي',
            'notes'            => 'مقاومة للتمزّق، تستخدم للشحن.',
        ]);
    }
}
