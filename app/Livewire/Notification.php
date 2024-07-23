<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class Notification extends Component
{
    public bool $visible = false;

    #[On('notify')]
    public function showComponent()
    {
        $this->visible = true;
    }

    public function render()
    {
        return view('livewire.notification');
    }
}
