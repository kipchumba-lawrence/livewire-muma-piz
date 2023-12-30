<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\pipeline;

class ViewEdit extends Component
{   public $shoot;
    public $link;
    public function render()
    {
        return view('livewire.view-edit');
    }
    public function mount($id){
        $this->shoot=pipeline::where('id',$id)->first();
    }
    public function completeEdit(){
        $this->shoot->editing_status="complete";
        $this->shoot->export_link=$this->link;
        $this->shoot->save();
        return redirect()->route('editor-dashboard');
    }
}
