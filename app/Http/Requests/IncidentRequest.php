<?php

namespace App\Http\Requests;
use App\Models\People;
use App\Models\Category;
use App\Rules\PeopleArray;
use Illuminate\Foundation\Http\FormRequest;

class IncidentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'location'          => 'required',
            'location.latitude' => 'required',
            'location.longitude'=> 'required',
            'title'             => 'required|string|min:1|max:255',
            'category_id'       => 'required|in:'.Category::all('id')->implode('id', ', '),
            'date'              => 'required|date_format:d-m-Y H:i:s',
            'people'            => new PeopleArray,
            'created_at'        => 'nullable|date_format:d-m-Y H:i:s',
            'updated_at'        => 'nullable|date_format:d-m-Y H:i:s',
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'location.required'           => 'A location is required',
            'location.latitude.required'  => 'A location is must be longitude,latitude',
            'location.longitude.required' => 'A location is must be longitude,latitudesss',
            'title.required'              => 'A title is required',
            'people.array'                => 'A people is array',
            'date.date_format'            => 'A date is must be dd-mm-yyyy hour:min:sec',
            'created_at.date_format'      => 'A created_at is must be dd-mm-yyyy hour:min:sec',
            'updated_at.date_format'      => 'A updated_at is must be dd-mm-yyyy hour:min:sec',
        ];
    }

}
