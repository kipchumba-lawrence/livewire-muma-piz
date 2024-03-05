<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Models\pipeline;

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
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer 8da1f85871050d19015289ffb13552976e466983',
        ])->post('https://lipia-api.kreativelabske.com/api/request/stk', [
            'phone' => $this->phone,
            'amount' => $this->amount,
        ]);

        if ($response->successful()) {
            $responseData = $response->json(); // Decoding JSON response
            $amount = $responseData['data']['amount'];
            $pipeline = pipeline::where('id', $id)->first();
            $pipeline->update([
                'payment_status' => 'paid',
                'paid_amount' => $amount,
            ]);
            $this->paymentStatus = "Payment Sucessful.";
        }
        $this->paymentStatus = "Payment Unsucessful.";
    }
    public function render()
    {
        return view('livewire.client-booking');
    }
}
