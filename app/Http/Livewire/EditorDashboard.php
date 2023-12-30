<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\pipeline;

class EditorDashboard extends Component
{
    public $pending_edits;
    public function render()
    {
        return view('livewire.editor-dashboard');
    }
    public function mount(){
        $this->pending_edits = Pipeline::where('editing_status', 'pending')
    ->where('shoot_status', 'completed')->where('editor', auth()->user()->id)
    ->orderBy('booked_time', 'asc')
    ->get();
    }
}
