<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\pipeline;
use App\Traits\Loggable;
use Iankumu\Mpesa\Facades\Mpesa;

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

    /**
     * Format phone number for M-Pesa (convert 07XX to 2547XX format)
     */
    protected function formatPhoneForMpesa(string $phone): string
    {
        // Remove any spaces or special characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // If starts with 0, replace with 254
        if (str_starts_with($phone, '0')) {
            $phone = '254' . substr($phone, 1);
        }
        
        // If doesn't start with 254, add it
        if (!str_starts_with($phone, '254')) {
            $phone = '254' . $phone;
        }
        
        return $phone;
    }

    public function payment()
    {
        $formattedPhone = $this->formatPhoneForMpesa($this->phone);
        
        $this->logMpesaTransaction('stk_push_initiated', [
            'phone' => $formattedPhone,
            'amount' => $this->amount,
            'account_reference' => '4122547'
        ]);

        try {
            $response = Mpesa::stkpush($formattedPhone, $this->amount, '4122547', 'https://mumaapix.com/api/payment');
            $response = json_decode((string)$response, true);

            // Check if STK push was successful
            if (!isset($response['MerchantRequestID']) || !isset($response['CheckoutRequestID'])) {
                $this->logMpesaTransaction('stk_push_failed', [
                    'phone' => $formattedPhone,
                    'response' => $response
                ]);
                
                session()->flash('error', 'Failed to initiate M-Pesa payment. Please try again.');
                return;
            }

            // Create the booking record with payment pending
            pipeline::create([
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
                'payment_status' => 'pending',
                'pipeline_status' => 'pending',
                'shoot_status' => 'pending',
                'editing_status' => 'pending',
                'social_consent' => $this->socialConsent ? true : false,
                'merchant_request_id' => $response['MerchantRequestID'],
                'checkout_request_id' => $response['CheckoutRequestID']
            ]);

            $this->logMpesaTransaction('stk_push_success', [
                'phone' => $formattedPhone,
                'merchant_request_id' => $response['MerchantRequestID'],
                'checkout_request_id' => $response['CheckoutRequestID']
            ]);

            return redirect()->route('client-booking')->with('paymentStatus', 'Payment prompt sent! Please check your phone and enter your M-Pesa PIN.');

        } catch (\Exception $e) {
            $this->logMpesaTransaction('stk_push_error', [
                'phone' => $formattedPhone,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', 'M-Pesa service error: ' . $e->getMessage());
            return;
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
            // Calculate dateTimeBooked and amount BEFORE calling payment
            $this->dateTimeBooked = Carbon::parse("{$this->scheduleDate} {$this->time}");

            // Calculate amount based on venue
            if ($this->venue == "outdoor") {
                $this->amount = 5;
            } else {
                $this->amount = 2;
            }

            // Now initiate payment with the calculated values
            return $this->payment();

        } catch (\Exception $e) {
            $this->logError('Client booking failed', [
                'email' => $this->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Booking failed: ' . $e->getMessage());
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
