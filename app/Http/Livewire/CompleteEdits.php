<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\pipeline;

class CompleteEdits extends Component
{
    public $completeEdits;
    public function render()
    {
        $this->completeEdits = pipeline::where('editing_status', 'complete')->get();
        return view('livewire.complete-edits');
    }
}
