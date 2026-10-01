<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Fix: Match the lowercase route parameter name '{user}' or get the model directly
        $user = $this->route('user');
        $userId = $user instanceof \App\Models\User ? $user->id : $user;

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($userId),
            ],
            // Fix: Will now correctly recognize that a user exists and make password optional on update
            'password' => $userId ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ];
    }
    // public function rules()
    // {
    //     $userId = $this->route('User') ? $this->route('User')->id : null;

    //     return [
    //         'name' => 'required|string|max:255',
    //         'email' => [
    //             'required',
    //             'email',
    //             Rule::unique('users')->ignore($userId),
    //         ],
    //         'password' => $userId ? 'nullable|string|min:8' : 'required|string|min:8',
    //         'roles' => 'nullable|array',
    //         'roles.*' => 'exists:roles,id',
    //     ];
    // }


}
