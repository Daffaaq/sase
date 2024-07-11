<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManajemenUserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('uuid'); // Assuming 'uuid' is the route parameter

        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $userId . ',uuid',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userId . ',uuid',
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:superadmin,kadiv,pegawai',
            'status' => 'required|in:aktif,nonaktif',
        ];
    }
}
