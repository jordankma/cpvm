<?php

namespace Adtech\Core\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
                    'menu_id' => 'required'
                ];
            }
            case 'POST': {
                return [
                    'name' => 'required',
                    'route_name' => 'required'
                ];
            }
            case 'PUT':{
                return [
                    'menu_id' => 'required',
                    'name' => 'required',
                    'alias' => 'required'
                ];
            }
            case 'PATCH':
            default:
                break;
        }
    }
}
