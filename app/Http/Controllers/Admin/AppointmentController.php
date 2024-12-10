<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentStoreRequest;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use App\Services\AppointmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AppointmentController extends Controller
{


    public function __construct(private readonly AppointmentService $service)
    {
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('admin.appointment.index',
            [
                'appointments' => Appointment::all()->map(fn($appointment) => [
                    'id' => $appointment->id,
                    'title' => $appointment->name,
                    'start' => $appointment->start,
                    'description' => $appointment->description,
                    'contact' => $appointment->contact,
                    'end' => $appointment->end
                ]
                )->toArray(),
                'barbers' => User::whereHas('roles', function ($query) {
                    $query->where('name', 'barber');
                })->get(),
                'services' => Service::all(),
            ]);
    }

    public function store(AppointmentStoreRequest $request): RedirectResponse
    {
        $this->service->store($request);

        return redirect()->route('admin.appointment.index');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();
        return redirect()->route('admin.appointment.index');
    }


}
