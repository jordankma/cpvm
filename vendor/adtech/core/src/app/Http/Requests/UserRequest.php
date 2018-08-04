<?php

namespace Adtech\Core\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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

        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [
                    'user_id' => 'required'
                ];
            }
            case 'POST': {
                return [
                    'contact_name' => 'required|min:3',
                    'email' => 'required|email|unique:mysql_core.adtech_core_users,email',
                    'password' => 'required|between:3,32',
                    'password_confirm' => 'required|same:password'
                ];
            }
            case 'PUT':{
                return [
                    'user_id' => 'required',
                    'contact_name' => 'required|min:3'
                ];
            }
            case 'PATCH':
            default:
                break;
        }
    }
}
