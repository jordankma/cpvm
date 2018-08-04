<?php

namespace Adtech\Core\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
                    'role_id' => 'required|numeric'
                ];
            }
            case 'POST': {
                return [
                    'name' => 'required'
                ];
            }
            case 'PUT':{
                return [
                    'role_id' => 'required|numeric',
                    'name' => 'required'
                ];
            }
            case 'PATCH':
            default:
                break;
        }
    }
}
