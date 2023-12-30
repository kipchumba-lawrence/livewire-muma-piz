<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\pipeline;

class ViewShoot extends Component
{   
     public $shoot; 
     public $pixisit;   
     public $numberofpix;
    public function render()
    {
        
        return view('livewire.view-shoot');
    }
    public function mount($id){
        $this->shoot=pipeline::where('id',$id)->first();
    }
    public function completeShoot(){
        $this->shoot->shoot_status="completed";
        $randomEditorId = User::inRandomOrder()->where('role', 'editor')->value('id');
        $this->shoot->editor = $randomEditorId;
        $this->shoot->image_collection = $this->pixisit;
        $this->shoot->numberofpix=$this->numberofpix;
        $this->shoot->editstart = Carbon::now();
        $this->shoot->save();
        return redirect()->route('photo-dashboard');
    }
}
