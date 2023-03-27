<?php

namespace App\Http\Requests;

use App\Permission\Permission;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->cannot(Permission::CAN_ACCESS_BOOKS)){
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
            'genre_id' => 'required',
            'judul_buku' => 'required|string',
            'penulis' => 'required|string',
            'penerbit' => 'required|string',
            'tahun_terbit' => 'required|numeric|between:1970,2023|digits:4',
            'sinopsis' => 'required',
        ];
    }
}
