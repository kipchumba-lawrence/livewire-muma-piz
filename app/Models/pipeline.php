<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pipeline extends Model
{
    protected $fillable = [
        'customer_name',
        'booked_time',
        'package',
        'venue',
        'note',
        'phone',
        'editor',
        'editing_status',
        'shoot_status',
        'image_collection',
        'export_link',
        'payment_status',
        'editstart',
        'total_amount',
        'numberofpix',
        'paid_amount'
    ];
    use HasFactory;
}
