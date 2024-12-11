<?php

namespace App\Http\Requests;

use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class AppointmentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'service_id' => 'required|integer|exists:services,id',
            'name' => 'required|string',
            'contact' => 'nullable|string',
            'description' => 'nullable|string',
            'start' => 'required|date|after:yesterday',
            'end' => 'required|date',
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if ($this->barber_id) {
            $user = User::whereId($this->barber_id)->first();
        }

        $service = Service::find($this->service_id);

        $timeStart = $this->start . $this->time;

        $timeToAdd = Carbon::parse($service->duration);

        $this->merge([
            'name' => $this->name,
            'start' => Carbon::parse($timeStart)->format('Y-m-d\TH:i:s'),
            'end' => Carbon::parse($timeStart)->addHours($timeToAdd->hour)
                ->addMinutes($timeToAdd->minute)
                ->addSeconds($timeToAdd->second)
                ->format('Y-m-d\TH:i:s'),
        ]);

        if ($this->barber_id) {
            $user = User::whereId($this->barber_id)->first();

            $this->merge([
                'name' => $this->name . ' (' . Str::ucfirst(Str::limit($user->name, 3, '')).')'
            ]);
        }
        unset($this->time);
    }

}
