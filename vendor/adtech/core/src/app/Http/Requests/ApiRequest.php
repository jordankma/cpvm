<?php

namespace Adtech\Core\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest
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
            case 'GET': {
                return [
                    'api_id' => 'required'
                ];
            }
            case 'DELETE': {
                return [
                    'api_id' => 'required'
                ];
            }
            case 'POST': {
                return [
                    'package_id' => 'required',
                    'name' => 'required',
                    'link' => 'required|unique:mysql_core.adtech_core_api,link'
                ];
            }
            case 'PUT':{
                return [
                    'api_id' => 'required',
                    'name' => 'required',
                    'link' => 'required'
                ];
            }
            case 'PATCH':
            default:
                break;
        }
    }
}
