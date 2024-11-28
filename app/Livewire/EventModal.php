<?php

namespace App\Livewire;

use App\Models\Appointment;
use Livewire\Attributes\On;
use Livewire\Component;

class EventModal extends Component
{
    public int $id = 0;
    public string|null $title, $description, $start, $end;

    public bool $showModal = false;

    /**
     * @param $event
     * @return void
     */
    #[On('openEventModal')]
    public function openEventModal($event): void
    {
        $appointment = collect($event)->get('event');

        $this->id = (int)$appointment['id'];
        $this->title = $appointment['title'];
        $this->description = $appointment['extendedProps']['description'];
        $this->start = $appointment['start'];
        $this->end = $appointment['end'];
        $this->showModal = true;
    }


    public function render()
    {
        return view('livewire.event-modal');
    }
}
