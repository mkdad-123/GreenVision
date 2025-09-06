<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropSale extends Model
{
    use HasFactory;
    protected $table = 'crop_sales';

    protected $fillable = [
        'user_id',
        'farm_id',
        'crop_name',
        'quantity',
        'unit',
        'price_per_unit',
        'total_price',
        'sale_date',
        'status',
        'buyer_name',
        'delivery_location',
        'notes',
    ];

    protected $casts = [
        'sale_date'      => 'date',      // أو 'datetime' حسب نوع العمود
        'delivery_date'  => 'date',
        'quantity'       => 'float',
        'price_per_unit' => 'float',
        'total_price'    => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
