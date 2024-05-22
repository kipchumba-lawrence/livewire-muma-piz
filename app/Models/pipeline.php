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
        'email',
        'phone',
        'editor',
        'editing_status',
        'shoot_status',
        'image_collection',
        'export_link',
        'pipeline_status',
        'payment_status',
        'editstart',
        'total_amount',
        'numberofpix',
        'paid_amount',
        'checkout_request_id',
        'merchant_request_id'
    ];
    use HasFactory;
}
