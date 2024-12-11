<?php

namespace App\Livewire;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
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

    /**
     * @return void
     */
    #[On('showCreateUserModal')]
    public function showCreateUserModal(): void
    {
        $this->reset(['name', 'email']);
        $this->show = true;
    }

    /**
     * @return void
     */
    public function closeModal(): void
    {
        $this->show = false;
    }

    /**
     * @return void
     */
    public function create(): void
    {

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->email.Carbon::now()->timestamp),
            ]);

            $user->assignRole('Barber');

            $this->dispatch('user-created', ['message' => 'Utente creato correttamente!']);

            DB::commit();


        } catch (\Exception $exception) {

            DB::rollBack();
            Log::error($exception->getMessage());

        }

        $this->dispatch('recordUpdated');
        $this->closeModal();

    }


    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.create-user-modal');
    }
}
