<?php

namespace App\Services;

use App\Http\Requests\AppointmentStoreRequest;
use App\Models\Appointment;

class AppointmentService {

    public function store(AppointmentStoreRequest $request)
    {
        Appointment::create($request->validated());
    }

}
