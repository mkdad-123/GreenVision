<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialRecord extends Model
{
    use HasFactory;
    protected $table = 'financial_records';

    protected $fillable = [
        'user_id',
        'direction',
        'category',
        'amount',
        'date',
        'description',
        'reference_number',
    ];

        protected $casts = [
        'date'      => 'date',      // أو 'datetime' حسب نوع العمود
        'amount'       => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
