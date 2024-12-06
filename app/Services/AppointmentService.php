<?php

namespace App\Services;

use App\Http\Requests\AppointmentStoreRequest;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppointmentService {

    public function store(AppointmentStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            Appointment::create($request->validated());
            session()->flash('success', 'Appuntamento creato correttamente!');
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
        }
    }

}
