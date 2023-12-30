<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\pipeline;

class PhotoDashboard extends Component
{   
    public $pending_shoots;
    public function render()
    {
        return view('livewire.photo-dashboard');
    }
    public function mount(){
        $this->pending_shoots=pipeline::where('shoot_status',"pending")->orderby('booked_time','asc')->get();
    }

}
