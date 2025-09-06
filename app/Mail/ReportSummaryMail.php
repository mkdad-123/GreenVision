<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportSummaryMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $report;
    public string $explanation;

    public function __construct($report, string $explanation)
    {
        // توحيد إلى Array
        if ($report instanceof \Illuminate\Http\JsonResponse) {
            $this->report = $report->getData(true);
        } elseif ($report instanceof \Illuminate\Support\Collection) {
            $this->report = $report->toArray();
        } elseif (is_array($report)) {
            $this->report = $report;
        } else {
            $this->report = ['raw' => $report];
        }

        $this->explanation = $explanation;
    }

    public function build()
    {
        return $this->subject('ملخص التقرير الزراعي')
                    ->view('emails.reports.summary')
                    ->with([
                        'report' => $this->report,
                        'explanation' => $this->explanation,
                    ]);
    }
}
