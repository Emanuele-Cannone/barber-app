<?php

namespace App\Http\Requests;

use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

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

        $service = Service::find($this->service_id);

        $timeStart = $this->start . $this->time;

        $timeToAdd = Carbon::parse($service->duration);

        $this->merge([
            'start' => Carbon::parse($timeStart)->format('Y-m-d\TH:i:s'),
            'end' => Carbon::parse($timeStart)->addHours($timeToAdd->hour)
                ->addMinutes($timeToAdd->minute)
                ->addSeconds($timeToAdd->second)
                ->format('Y-m-d\TH:i:s'),
        ]);

        unset($this->time);
    }

}
