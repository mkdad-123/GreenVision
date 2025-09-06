@php
    $salesCount = (int) data_get($report, 'totals.sales_count', 0);
    $totalRevenue = (float) data_get($report, 'totals.gross_revenue', 0);
    $totalExpenses = (float) data_get($report, 'totals.expenses', 0);
    $netProfit = (float) data_get($report, 'totals.net_profit', $totalRevenue - $totalExpenses);

    $salesByCrop = data_get($report, 'breakdowns.sales_by_crop', []);
    $expenseByCat = data_get($report, 'breakdowns.expense_by_category', []);
    $incomeByCat = data_get($report, 'breakdowns.income_by_category', []);

    $periodFrom = data_get($report, 'period.from');
    $periodTo = data_get($report, 'period.to');
    $farmId = data_get($report, 'filters.farm_id');
    $saleStatus = data_get($report, 'filters.sale_status');

    $autoExplanation =
        "يشمل هذا التقرير الفترة من {$periodFrom} إلى {$periodTo} مع حالة المبيعات: {$saleStatus}." .
        ($farmId ? " وتمت تصفية النتائج على مزرعة رقم {$farmId}." : '');
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ملخص التقرير الزراعي</title>
    <style>
        body {
            margin: 0;
            background: #f4f8f4;
        }

        .outer {
            width: 100%;
            background: #f4f8f4;
        }

        .wrap {
            max-width: 720px;
            margin: 0 auto;
            background: #ffffff;
        }

        .rtl {
            direction: rtl;
            text-align: right;
            font-family: Tahoma, Arial, sans-serif;
            color: #173417;
        }

        .header-grad {
            background: linear-gradient(135deg, #43a047, #2e7d32);
        }

        .shadow {
            box-shadow: 0 8px 24px rgba(0, 0, 0, .06);
        }

        .rounded {
            border-radius: 16px;
        }

        .badge {
            display: inline-block;
            background: rgba(255, 255, 255, .18);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .28);
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1b5e20;
            margin: 16px 0 8px
        }

        .card {
            background: #e8f5e9;
            border: 1px solid #daefdb;
            border-radius: 12px;
            padding: 14px
        }

        .card h4 {
            margin: 0 0 6px;
            font-size: 14px;
            color: #1b5e20
        }

        .card .val {
            font-size: 20px;
            font-weight: 700;
            color: #2e7d32
        }

        .muted {
            color: #2f4f2f;
            font-size: 13px
        }

        .pill {
            display: inline-block;
            padding: 4px 8px;
            background: #fff3cd;
            color: #7a5900;
            border: 1px solid #ffe08a;
            border-radius: 999px;
            font-size: 12px
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ecf2ec
        }

        .table th {
            background: #f3faf3;
            color: #2a5a2a;
            font-size: 13px;
            padding: 10px 12px;
            border-bottom: 1px solid #ecf2ec
        }

        .table td {
            font-size: 13px;
            padding: 10px 12px;
            border-bottom: 1px solid #f0f5f0
        }

        .footer {
            background: #f9fcf9;
            border-top: 1px solid #ecf2ec;
            color: #456;
            font-size: 12px
        }

        .preheader {
            display: none !important;
            visibility: hidden;
            opacity: 0;
            color: transparent;
            height: 0;
            width: 0;
            overflow: hidden;
            mso-hide: all
        }

        @media (max-width:520px) {
            .stack td {
                display: block;
                width: 100% !important
            }
        }
    </style>
</head>

<body class="rtl">
    <!-- نص معاينة قصير لبعض عملاء البريد -->
    <div class="preheader">ملخص تقريرك الزراعي: المبيعات والإيرادات والنفقات وصافي الربح للفترة المحددة.</div>

    <table class="outer" role="presentation" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="center" style="padding:24px 12px;">
                <table class="wrap shadow rounded" role="presentation" cellspacing="0" cellpadding="0" border="0"
                    width="100%">
                    <!-- Header -->
                    <tr>
                        <td class="header-grad rounded"
                            style="padding:24px 24px 28px;border-top-left-radius:16px;border-top-right-radius:16px;">
                            <h1 style="margin:0;color:#fff;font-size:22px;font-weight:700;">ملخص التقرير الزراعي</h1>
                            <p style="margin:8px 0 0;color:#f1fff1;font-size:14px;line-height:1.6;">
                                {{ $explanation ?? $autoExplanation }}
                            </p>
                            <div style="margin-top:10px;"><span class="badge">تقرير تلقائي من نظامك الزراعي</span>
                            </div>
                        </td>
                    </tr>

                    <!-- KPI cards (جدول لضمان التوافق) -->
                    <tr>
                        <td style="padding:18px 18px 6px;background:#ffffff;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" class="stack">
                                <tr>
                                    <td style="padding:6px;vertical-align:top;width:50%;">
                                        <div class="card">
                                            <h4>عدد عمليات البيع</h4>
                                            <div class="val">{{ number_format($salesCount) }}</div>

                                        </div>
                                    </td>
                                    <td style="padding:6px;vertical-align:top;width:50%;">
                                        <div class="card">
                                            <h4>إجمالي الإيرادات</h4>
                                            <div class="val">{{ number_format($totalRevenue, 2) }} <span
                                                    class="muted">عملة</span></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:6px;vertical-align:top;width:50%;">
                                        <div class="card">
                                            <h4>إجمالي النفقات</h4>
                                            <div class="val">{{ number_format($totalExpenses, 2) }} <span
                                                    class="muted">عملة</span></div>
                                        </div>
                                    </td>
                                    <td style="padding:6px;vertical-align:top;width:50%;">
                                        <div class="card">
                                            <h4>صافي الربح</h4>
                                            <div class="val"
                                                style="color:{{ $netProfit >= 0 ? '#2e7d32' : '#b71c1c' }};">
                                                {{ number_format($netProfit, 2) }} <span class="muted">عملة</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- تفصيل حسب المحصول -->
                    @if (!empty($salesByCrop))
                        <tr>
                            <td style="padding:10px 18px 0;">
                                <div class="section-title">تفصيل حسب المحصول</div>
                                <table class="table" role="presentation">
                                    <thead>
                                        <tr>
                                            <th align="right">المحصول</th>
                                            <th align="right">الكمية</th>
                                            <th align="right">قيمة المبيعات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($salesByCrop as $row)
                                            <tr>
                                                <td>{{ $row['crop_name'] ?? '-' }}</td>
                                                <td>{{ number_format($row['total_quantity'] ?? 0) }}</td>
                                                <td>{{ number_format($row['total_sales'] ?? 0, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endif

                    <!-- النفقات حسب التصنيف -->
                    @if (!empty($expenseByCat))
                        <tr>
                            <td style="padding:14px 18px 0;">
                                <div class="section-title">النفقات حسب التصنيف</div>
                                <table class="table" role="presentation">
                                    <thead>
                                        <tr>
                                            <th align="right">التصنيف</th>
                                            <th align="right">الإجمالي</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($expenseByCat as $e)
                                            <tr>
                                                <td>{{ $e['category'] ?? '-' }}</td>
                                                <td>{{ number_format($e['total'] ?? 0, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endif

                    <!-- الدخل حسب التصنيف -->
                    @if (!empty($incomeByCat))
                        <tr>
                            <td style="padding:14px 18px 0;">
                                <div class="section-title">الدخل حسب التصنيف</div>
                                <table class="table" role="presentation">
                                    <thead>
                                        <tr>
                                            <th align="right">التصنيف</th>
                                            <th align="right">الإجمالي</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($incomeByCat as $i)
                                            <tr>
                                                <td>{{ $i['category'] ?? '-' }}</td>
                                                <td>{{ number_format($i['total'] ?? 0, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endif

                    <!-- ملاحظة -->
                    <tr>
                        <td style="padding:16px 18px;">
                            <div class="muted" style="line-height:1.7;">
                                <span class="pill">ملاحظة</span>
                                هذا التقرير مُولِّد آليًا وفق البيانات المدخلة. يرجى التأكد من دقة الإدخالات للحصول على
                                نتائج أكثر موثوقية.
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="footer"
                            style="padding:14px 18px;border-bottom-left-radius:16px;border-bottom-right-radius:16px;">
                            تم الإرسال بواسطة نظام التقارير الزراعي — إذا لم تطلب هذا التقرير، يمكنك تجاهل هذا البريد.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
