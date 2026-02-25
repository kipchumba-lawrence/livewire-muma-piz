<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\pipeline;
use App\Traits\Loggable;
use Iankumu\Mpesa\Facades\Mpesa;
use Illuminate\Support\Facades\Log;

class ClientBooking extends Component
{
    use Loggable;
    public $bookingStatus = NULL;
    public $paymentStatus = NULL;

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
        if (session()->has('paymentStatus')) {
            $this->paymentStatus = session('paymentStatus');
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

            if ($this->venue == "outdoor") {
                $this->amount = 5;
            } else {
                $this->amount = 2;
            }

            if ($this->venue === 'walkin') {
                $this->createBooking('paid', 'walk-in');
            } else {
                $this->createBookingWithPayment();
            }
        } catch (\Exception $e) {
            $this->logError('Client booking failed', [
                'email' => $this->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('bookingError', 'Something went wrong. Please try again.');
        }
    }

    public function createBookingWithPayment()
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
                'paid_amount' => 0,
                'total_amount' => $this->amount,
                'payment_status' => 'pending',
                'pipeline_status' => 'pending',
                'shoot_status' => 'pending',
                'editing_status' => 'pending',
                'social_consent' => $this->socialConsent ? true : false
            ]);

            $accountReference = 'MMP' . $pipeline->id;
            $response = Mpesa::stkpush($this->phone, $this->amount, $accountReference);
            $result = json_decode((string) $response);

            $this->logMpesaTransaction('stk_push_initiated', [
                'pipeline_id' => $pipeline->id,
                'phone' => $this->phone,
                'amount' => $this->amount,
                'response' => (array) $result,
            ]);

            if (isset($result->MerchantRequestID) && isset($result->CheckoutRequestID)) {
                $pipeline->update([
                    'merchant_request_id' => $result->MerchantRequestID,
                    'checkout_request_id' => $result->CheckoutRequestID,
                ]);

                $this->logPipelineAction('mpesa_stk_sent', $pipeline->id, [
                    'merchant_request_id' => $result->MerchantRequestID,
                    'checkout_request_id' => $result->CheckoutRequestID,
                ]);

                return redirect()->route('client-booking')
                    ->with('bookingStatus', 'Booking created! Please check your phone and enter your M-Pesa PIN to complete payment.')
                    ->with('paymentStatus', 'pending');
            } else {
                $errorMsg = $result->errorMessage ?? ($result->ResponseDescription ?? 'STK push failed');
                $pipeline->update(['payment_status' => 'failed']);

                $this->logError('STK push response error', [
                    'pipeline_id' => $pipeline->id,
                    'response' => (array) $result,
                ]);

                return redirect()->route('client-booking')
                    ->with('bookingStatus', 'Booking created but payment could not be initiated: ' . $errorMsg)
                    ->with('paymentStatus', 'failed');
            }
        } catch (\Exception $e) {
            $this->logError('Booking with payment failed', [
                'phone' => $this->phone,
                'email' => $this->email,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('client-booking')
                ->with('bookingStatus', 'Booking created but payment could not be processed. Please contact support.')
                ->with('paymentStatus', 'error');
        }
    }
    
    public function createBooking($paymentStatus = 'paid', $paymentType = 'walk-in')
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
                'payment_status' => $paymentStatus,
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
                'payment_type' => $paymentType,
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
