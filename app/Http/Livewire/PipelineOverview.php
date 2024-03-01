<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\pipeline;

class PipelineOverview extends Component
{
    public $pipelines;
    public function render()
    {
        return view('livewire.pipeline-overview');
    }
    public function mount()
    {
        $this->pipelines = pipeline::where('pipeline_status', 'pending')->where("payment_status", 'paid')->get();
    }
}
