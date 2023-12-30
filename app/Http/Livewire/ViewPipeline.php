<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\pipeline;

class ViewPipeline extends Component
{
    public $pipeline;
    public function render()
    {
        return view('livewire.view-pipeline');
    }
    public function mount($id){
        $this->pipeline=pipeline::where('id',$id)->first();
    }
}
