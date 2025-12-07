<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class pipeline extends Model
{
    use HasFactory, LogsActivity;

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
        'merchant_request_id',
        'hair',
        'outfit',
        'makeup',
        'redit',
        'reditails'
    ];

    /**
     * Activity log configuration
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'customer_name',
                'booked_time',
                'package',
                'editing_status',
                'shoot_status',
                'pipeline_status',
                'payment_status',
                'total_amount',
                'paid_amount'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Pipeline {$eventName}");
    }
}
