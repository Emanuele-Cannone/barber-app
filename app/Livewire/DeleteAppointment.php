<?php

namespace App\Livewire;

use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class DeleteAppointment extends Component
{

    #[On('delete-appointment')]
    public function deleteAppointment($id)
    {
        $appointment = Appointment::find($id);

        try{
            DB::beginTransaction();
            $appointment->delete();
            DB::commit();

            $this->dispatch('event-deleted', ['message' => 'Appuntamento eliminato correttamente!']);
        } catch (\Exception $exception){
            DB::rollBack();
            Log::error($exception->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.delete-appointment');
    }
}
