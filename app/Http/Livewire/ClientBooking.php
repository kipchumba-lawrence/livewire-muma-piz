<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\pipeline;
use Iankumu\Mpesa\Facades\Mpesa;
use Illuminate\Support\Facades\Http;

class ClientBooking extends Component
{
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
    public function mount()
    {
        if (session()->has('paymentStatus')) {
            $this->paymentStatus = session('paymentStatus');
        }
    }
    public function save()
    {
        $this->dateTimeBooked = Carbon::parse("{$this->scheduleDate} {$this->time}");

        // Save the booking details to the database using Eloquent
        $this->payment();
        $this->paymentStatus = "Pending Confirmation";
        if ($this->venue == "outdoor") {
            $this->amount = 5;
        } else {
            $this->amount = 2;
        }
    }
    public function payment()
    {
        $response = Mpesa::stkpush($this->phone, 1, '4122547', 'https://test.preshamafeedsltd.com/api/payment');
        $response = json_decode((string)$response, true);
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
            'merchant_request_id' =>  $response['MerchantRequestID'],
            'checkout_request_id' =>  $response['CheckoutRequestID']
        ]);
        return redirect()->route('client-booking')->with('paymentStatus', 'Pending Payment Confirmation.');
    }
    public function render()
    {
        return view('livewire.client-booking');
    }
}
