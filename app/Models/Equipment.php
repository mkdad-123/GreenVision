<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;
    protected $table = 'equipments';
    protected $fillable = [
        'user_id',
        'name',
        'serial_number',
        'purchase_date',
        'last_maintenance',
        'next_maintenance',
        'status',
        'type',
        'location',
        'usage_hours',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
