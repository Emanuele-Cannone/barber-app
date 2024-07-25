<?php

namespace App\Livewire;

use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateServiceModal extends Component
{
    #[Validate('required|string|max:255|unique:services,name')]
    public string $name;

    #[Validate('required|string')]
    public string $description;

    #[Validate(['required','string','regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'])]
    public string $duration;

    #[Validate('boolean')]
    public bool $show = false;

    #[On('showCreateServiceModal')]
    public function showCreateServiceModal()
    {
        $this->reset(['name', 'description', 'duration']);
        $this->show = true;
    }

    public function closeModal()
    {
        $this->show = false; // Nasconde la modale
    }

    public function create()
    {

        try {
            DB::beginTransaction();

            Service::create([
                'name' => $this->name,
                'description' => $this->description,
                'duration' => $this->duration,
            ]);

            DB::commit();

            $this->dispatch('notify');

        } catch (\Exception $exception) {

            DB::rollBack();
            Log::error($exception->getMessage());

        }

        $this->dispatch('recordInserted');
        $this->closeModal();

    }


    public function render()
    {
        return view('livewire.create-service-modal');
    }
}
