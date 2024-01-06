<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\pipeline;

class CompletedPipelines extends Component
{
    public $pipelines;
    public function render()
    {
        return view('livewire.completed-pipelines');
    }
    public function mount(){
        $this->pipelines = pipeline::where('pipeline_status','complete')->get();
    }
    public function revert($id){
        $single_pipe=pipeline::where('id',$id)->first();
        $single_pipe->update([
            'pipeline_status' => 'pending',
        ]);
        redirect()->route('completed-pipelines')->with('message','Reverted Successfully');
      }

}
