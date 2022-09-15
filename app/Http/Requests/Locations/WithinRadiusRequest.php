<?php

namespace App\Http\Requests\Locations;

use App\Rules\Geolocation;
use Illuminate\Foundation\Http\FormRequest;

use function app;

class WithinRadiusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'rad' => ['required', 'numeric'],
            'lat' => ['required', app(Geolocation\Latitude::class)],
            'long' => ['required', app(Geolocation\Longitude::class)],
        ];
    }
}
