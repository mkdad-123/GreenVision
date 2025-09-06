<?php

namespace App\Helpers;

class LabelTranslator{

    public static function toArabic(?string $label): ?string
{
    if (!$label) return null;

    // 1) قاموس ترجمة مباشر (الأكثر دقة)
    static $directMap = [
        'Apple___Apple_scab' => 'تفاح — جرب التفاح',
        'Apple___Black_rot' => 'تفاح — العفن الأسود',
        'Apple___Cedar_apple_rust' => 'تفاح — صدأ التفاح (الأرزّي)',
        'Apple___healthy' => 'تفاح — سليمة',

        'Blueberry___healthy' => 'توّت — سليمة',

        'Cherry_(including_sour)___Powdery_mildew' => 'كرز — البياض الدقيقي',
        'Cherry_(including_sour)___healthy' => 'كرز — سليمة',

        'Corn_(maize)___Cercospora_leaf_spot Gray_leaf_spot' => 'ذرة — تبقع أوراق (سركسبورا/رمادي)',
        'Corn_(maize)___Common_rust_' => 'ذرة — صدأ شائع',
        'Corn_(maize)___Northern_Leaf_Blight' => 'ذرة — لفحة الأوراق الشمالية',
        'Corn_(maize)___healthy' => 'ذرة — سليمة',

        'Grape___Black_rot' => 'عنب — العفن الأسود',
        'Grape___Esca_(Black_Measles)' => 'عنب — إسكا (الجدري الأسود)',
        'Grape___Leaf_blight_(Isariopsis_Leaf_Spot)' => 'عنب — لفحة/تبقّع أوراق (إيساريوبسِس)',
        'Grape___healthy' => 'عنب — سليمة',

        'Orange___Haunglongbing_(Citrus_greening)' => 'حمضيات — التبقّع الأخضر/التخضير (HLB)',

        'Peach___Bacterial_spot' => 'خوخ — تبقّع بكتيري',
        'Peach___healthy' => 'خوخ — سليمة',

        'Pepper,_bell___Bacterial_spot' => 'فليفلة — تبقّع بكتيري',
        'Pepper,_bell___healthy' => 'فليفلة — سليمة',

        'Potato___Early_blight' => 'بطاطا — لفحة مبكرة',
        'Potato___Late_blight' => 'بطاطا — لفحة متأخرة',
        'Potato___healthy' => 'بطاطا — سليمة',

        'Raspberry___healthy' => 'توت علّيق — سليمة',
        'Soybean___healthy' => 'صويا — سليمة',

        'Squash___Powdery_mildew' => 'كوسا/قرع — البياض الدقيقي',

        'Strawberry___Leaf_scorch' => 'فريز — احتراق/لفحة الأوراق',
        'Strawberry___healthy' => 'فريز — سليمة',

        'Tomato___Bacterial_spot' => 'طماطم — تبقّع بكتيري',
        'Tomato___Early_blight' => 'طماطم — لفحة مبكرة',
        'Tomato___Late_blight' => 'طماطم — لفحة متأخرة',
        'Tomato___Leaf_Mold' => 'طماطم — عفن الأوراق',
        'Tomato___Septoria_leaf_spot' => 'طماطم — تبقّع أوراق سبْتوريا',
        'Tomato___Spider_mites Two-spotted_spider_mite' => 'طماطم — سوس العنكبوت (ثنائي البقع)',
        'Tomato___Target_Spot' => 'طماطم — تبقّع الهدف',
        'Tomato___Tomato_Yellow_Leaf_Curl_Virus' => 'طماطم — فيروس تَجعُّد واصفرار أوراق الطماطم (TYLCV)',
        'Tomato___Tomato_mosaic_virus' => 'طماطم — فيروس موزاييك الطماطم (ToMV)',
        'Tomato___healthy' => 'طماطم — سليمة',
    ];

    if (isset($directMap[$label])) {
        return $directMap[$label];
    }

    // 2) قواعد عامة (Fallback) لو الاسم غير موجود بالحرف
    // تقسيم "محصول___مرض"
    $parts = explode('___', $label, 2);
    $crop = $parts[0] ?? '';
    $disease = $parts[1] ?? '';

    // تنظيف بسيط للأسماء الإنجليزية
    $crop = str_replace(['_', ',', '(', ')'], [' ', '،', '(', ')'], $crop);
    $disease = str_replace(['_', ',', '(', ')'], [' ', '،', '(', ')'], $disease);
    $crop = trim($crop);
    $disease = trim($disease);

    // تحويل كلمات مفتاحية شائعة
    $keywords = [
        'healthy' => 'سليمة',
        'Bacterial spot' => 'تبقّع بكتيري',
        'Black rot' => 'العفن الأسود',
        'Cedar apple rust' => 'صدأ التفاح (الأرزّي)',
        'Apple scab' => 'جرب التفاح',
        'Powdery mildew' => 'البياض الدقيقي',
        'Cercospora leaf spot' => 'تبقّع أوراق سركسبورا',
        'Gray leaf spot' => 'تبقّع أوراق رمادي',
        'Northern Leaf Blight' => 'لفحة الأوراق الشمالية',
        'Leaf blight' => 'لفحة الأوراق',
        'Esca' => 'إسكا',
        'Leaf Mold' => 'عفن الأوراق',
        'Septoria leaf spot' => 'تبقّع أوراق سبْتوريا',
        'Spider mites' => 'سوس العنكبوت',
        'Two-spotted spider mite' => 'سوس العنكبوت ثنائي البقع',
        'Target Spot' => 'تبقّع الهدف',
        'Tomato Yellow Leaf Curl Virus' => 'فيروس تَجعُّد واصفرار أوراق الطماطم',
        'Tomato mosaic virus' => 'فيروس موزاييك الطماطم',
        'Haunglongbing' => 'التبقّع الأخضر/التخضير (HLB)',
        'Citrus greening' => 'التبقّع الأخضر/التخضير (HLB)',
        'Early blight' => 'لفحة مبكرة',
        'Late blight' => 'لفحة متأخرة',
        'Leaf scorch' => 'احتراق/لفحة الأوراق',
    ];

    // استبدال ذكي (غير حسّاس لحالة الأحرف البسيطة)
    foreach ($keywords as $en => $ar) {
        if (stripos($disease, $en) !== false) {
            $disease = preg_replace('/'.preg_quote($en, '/').'/i', $ar, $disease);
        }
    }

    // تعريب أسماء المحاصيل الأساسية (تقريبية)
    $cropMap = [
        'Apple' => 'تفاح',
        'Blueberry' => 'توّت',
        'Cherry' => 'كرز',
        'Cherry (including sour)' => 'كرز',
        'Corn (maize)' => 'ذرة',
        'Grape' => 'عنب',
        'Orange' => 'حمضيات',
        'Peach' => 'خوخ',
        'Pepper, bell' => 'فليفلة',
        'Potato' => 'بطاطا',
        'Raspberry' => 'توت علّيق',
        'Soybean' => 'صويا',
        'Squash' => 'كوسا/قرع',
        'Strawberry' => 'فريز',
        'Tomato' => 'طماطم',
    ];

    // محاولة مطابقة اسم المحصول
    $cropAr = $cropMap[$crop] ?? $crop;

    // // صيغة العرض النهائية
    // if ($disease === '' || stripos($disease, 'سليمة') !== false) {
    //     return "{$cropAr} — سليمة";
    // }
    return "{$cropAr} ";
}
}
