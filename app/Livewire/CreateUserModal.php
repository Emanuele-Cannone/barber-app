<?php

namespace App\Livewire;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateUserModal extends Component
{

    #[Validate('required|string|max:255')]
    public string $name;

    #[Validate('required|email|unique:users,email')]
    public string $email;

    public string $password;

    #[Validate('boolean')]
    public bool $show;

    #[On('showCreateUserModal')]
    public function showCreateUserModal()
    {
        $this->reset(['name', 'email']);
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

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->email.Carbon::now()->timestamp),
            ]);

            $user->assignRole('Barber');

            DB::commit();


        } catch (\Exception $exception) {

            DB::rollBack();
            Log::error($exception->getMessage());

        }

        $this->dispatch('recordUpdated');
        $this->closeModal();

    }


    public function render()
    {
        return view('livewire.create-user-modal');
    }
}
