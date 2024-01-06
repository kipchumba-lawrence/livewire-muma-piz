<?php
namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
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
            'package' => $this->package,
            'booked_time' => $this->dateTimeBooked,
            'note' => $this->note,
        ]);

        // Reset the form fields
        // $this->reset();
        // You can add a success message or redirect to another page after submission
        session()->flash('message', 'Booking details submitted successfully!');
        // $this->payment($pipeline->id);
        
    }
    public function payment($id)
    {
        $this->paymentStatus="Pending Confirmation";
        $url = "https://www.tinypesa.com/api/v1/express/initialize";
        $data = array(
            'amount' => 1,
            'msisdn' => 254727750214,
            'account_no' => $id,
        );
        $headers = array(
            "Content-Type: application/x-www-form-urlencoded",
            "ApiKey: c2B0fPWXKHS",
        );
    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            // Handle cURL error
            echo 'cURL error: ' . curl_error($ch);
        }
    
        curl_close($ch);
    
        // Output the response
        var_dump($response);
    }
    
}
