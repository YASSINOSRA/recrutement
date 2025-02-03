<?php

namespace App\Http\Requests;

use App\Models\HeaderSlider;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateHeaderSliderRequest
 */
class CreateHeaderSliderRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return HeaderSlider::$rules;
    }

    /**
     * @return array|string[]
     */
    public function messages(): array
    {
        return [
            'header_slider.required' => 'The image field is required.',
            'header_slider.mimes' => 'The image must be a file of type: jpeg, jpg, png.',
        ];
    }
}
