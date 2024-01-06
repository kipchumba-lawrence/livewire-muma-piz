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
    public function mount($id)
    {
        $this->pipeline = pipeline::where('id', $id)->first();
    }
    public function revert()
    {

        $this->pipeline->editing_status = "pending";
        $this->pipeline->save();
        redirect()->route('pipeline-overview')->with('message', 'Reverted Successfully');
    }

    public function complete()
    {

        $this->pipeline->pipeline_status = "complete";
        $this->pipeline->save();
        redirect()->route('pipeline-overview')->with('message', 'Reverted Successfully');
    }
}
