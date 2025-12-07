<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\pipeline;
use App\Traits\Loggable;

class ClientBooking extends Component
{
    use Loggable;
    public $bookingStatus = NULL;

    public $name;
    public $phone;
    public $venue;
    public $email;
    public $amount;
    public $package;
    public $scheduleDate;
    public $time;
    public $note;
    public $dateTimeBooked;
    public $outfit;
    public $makeup;
    public $hair;
    
    public $socialConsent = false;

    protected $rules = [
        'name' => 'required',
        'phone' => 'required|digits:10',
        'email' => 'required|email',
        'venue' => 'required',
        'package' => 'required',
        'scheduleDate' => 'required|date|after_or_equal:today',
        'time' => 'required',
    ];

    public function mount()
    {
        if (session()->has('bookingStatus')) {
            $this->bookingStatus = session('bookingStatus');
        }
    }
    
    public function save()
    {
        $this->validate();

        $this->logInfo('Client booking started', [
            'email' => $this->email,
            'phone' => $this->phone,
            'package' => $this->package,
            'venue' => $this->venue
        ]);

        try {
            $this->dateTimeBooked = Carbon::parse("{$this->scheduleDate} {$this->time}");

            // Calculate amount based on venue
            if ($this->venue == "outdoor") {
                $this->amount = 5;
            } else {
                $this->amount = 2;
            }

            // Create booking (walk-in, no payment processing)
            $this->createBooking();
            $this->bookingStatus = "Booking Confirmed";

            $this->logInfo('Client booking completed successfully', [
                'email' => $this->email,
                'amount' => $this->amount,
                'type' => 'walk-in'
            ]);
        } catch (\Exception $e) {
            $this->logError('Client booking failed', [
                'email' => $this->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }
    
    public function createBooking()
    {
        try {
            $pipeline = pipeline::create([
                'customer_name' => $this->name,
                'phone' => $this->phone,
                'venue' => $this->venue,
                'email' => $this->email,
                'package' => $this->package,
                'booked_time' => $this->dateTimeBooked,
                'note' => $this->note,
                'makeup' => $this->makeup,
                'hair' => $this->hair,
                'outfit' => $this->outfit,
                'paid_amount' => $this->amount,
                'total_amount' => $this->amount,
                'payment_status' => 'paid', // Set as paid for walk-in bookings
                'pipeline_status' => 'pending',
                'shoot_status' => 'pending',
                'editing_status' => 'pending',
                'social_consent' => $this->socialConsent ? true : false
            ]);

            $this->logPipelineAction('booking_created', $pipeline->id, [
                'customer_name' => $this->name,
                'package' => $this->package,
                'venue' => $this->venue,
                'booked_time' => $this->dateTimeBooked,
                'payment_type' => 'walk-in',
                'amount' => $this->amount,
                'social_consent' => $this->socialConsent
            ]);

            return redirect()->route('client-booking')->with('bookingStatus', 'Booking confirmed successfully!');
        } catch (\Exception $e) {
            $this->logError('Booking creation failed', [
                'phone' => $this->phone,
                'email' => $this->email,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
    public function render()
    {
        return view('livewire.client-booking');
    }
}
