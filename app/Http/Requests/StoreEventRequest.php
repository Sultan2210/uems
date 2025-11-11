<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize()
    {
        // only allow organizers
        return $this->user() && $this->user()->role === 'organizer';
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'venue' => 'nullable|string|max:255',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'has_certificate' => 'nullable|boolean',
        ];
    }
}
