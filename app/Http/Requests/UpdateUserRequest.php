<?php

namespace App\Http\Requests;

use App\Permission\Permission;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot(Permission::CAN_ACCESS_USERS)) {
            return redirect()->route('users.index');
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
            'name' => 'required|string',
            'username' => [
                'required',
                'string',
                Rule::unique('users')->ignore($this->user),
            ],
            'role' => 'required',
        ];
    }
}
