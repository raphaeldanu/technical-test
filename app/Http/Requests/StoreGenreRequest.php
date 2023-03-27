<?php

namespace App\Http\Requests;

use App\Permission\Permission;
use Illuminate\Foundation\Http\FormRequest;

class StoreGenreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->cannot(Permission::CAN_ACCESS_GENRES)){
            return redirect()->route('home')->with('Not Authorized');
        }
        
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
            'name' => 'required|string|unique:genres',
        ];
    }
}
