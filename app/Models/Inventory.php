<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $table = 'inventories';

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'quantity',
        'unit',
        'purchase_date',
        'expiry_date',
        'min_threshold',
        'supplier',
        'storage_location',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
