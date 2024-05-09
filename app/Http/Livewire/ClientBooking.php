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

    public function save()
    {
        $this->dateTimeBooked = Carbon::parse("{$this->scheduleDate} {$this->time}");

        // Save the booking details to the database using Eloquent
        $pipeline = pipeline::create([
            'customer_name' => $this->name,
            'phone' => $this->phone,
            'venue' => $this->venue,
            'email' => $this->email,
            'package' => $this->package,
            'booked_time' => $this->dateTimeBooked,
            'note' => $this->note,
        ]);
        session()->flash('message', 'Booking details submitted successfully!');
        $this->paymentStatus = "Pending Confirmation";
        if ($this->venue == "outdoor") {
            $this->amount = 1;
        } else {
            $this->amount = 2000;
        }
        $this->payment($pipeline->id);
    }
    public function payment($id)
    {
        $response = Mpesa::stkpush('0727750214', 1, '174379', 'https://mumaapix.com');
        $response = json_decode((string)$response);
        dd($response);
    }
    public function render()
    {
        return view('livewire.client-booking');
    }
}
