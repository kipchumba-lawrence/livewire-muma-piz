<?php
namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Models\pipeline;
class ClientBooking extends Component
{   public $paymentStatus=NULL;
    public function render()
    {
        return view('livewire.client-booking');
    }
    public $name;
    public $phone;
    public $venue;
    public $email;
    public $package;
    public $scheduleDate;
    public $time;
    public $note;
    public $dateTimeBooked;

    public function save()
    {   

        $this->dateTimeBooked = Carbon::parse("{$this->scheduleDate} {$this->time}");

        // Save the booking details to the database using Eloquent
        $pipeline=pipeline::create([
            'customer_name' => $this->name,
            'phone' => $this->phone,
            'venue' => $this->venue,
            'email' => $this->email,
            'package' => $this->package,
            'booked_time' => $this->dateTimeBooked,
            'note' => $this->note,
        ]);

        // Reset the form fields
        // $this->reset();
        // You can add a success message or redirect to another page after submission
        session()->flash('message', 'Booking details submitted successfully!');
        $this->paymentStatus="Pending Confirmation";
        $this->payment($pipeline->id);
        
    }
    public function payment($id)
    {         $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer 8da1f85871050d19015289ffb13552976e466983',
        ])->post('https://lipia-api.kreativelabske.com/api/request/stk', [
            'phone' => $this->phone,
            'amount' => '2',
        ]);
        // Handle the checking of the responses 
        dd($response);
    }
    
}
