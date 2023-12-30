<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\pipeline;

class CompleteShoots extends Component
{
    public $completeShoots;
    public function render()
    {
        return view('livewire.complete-shoots');
    }
    public function mount(){
        $this->completeShoots=pipeline::where('shoot_status','completed')->get();
    }
}
