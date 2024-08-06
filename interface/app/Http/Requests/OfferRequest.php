<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
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
            'url'          => 'required|url',
            'trackers'     => 'required|array|min:1',
            'trackers.*'   => 'exists:mysql_lc.trackers_settings_models,id',
            'geo'          => 'required|string|min:1',
            'language'     => 'required|string|min:1',
            'type'         => 'required|string|min:1',
            'category'     => 'required|string|min:1',
            'form_factor'  => 'required|string|min:1',
            'lp_numbering' => 'required|numeric|min:0',
            'name'         => 'required|string|min:1',
            'aff_network'  => 'required|string|min:1',
            'price'        => 'required|numeric|min:0',
            'offer_type'   => 'required|string|min:1',
        ];
    }
}
