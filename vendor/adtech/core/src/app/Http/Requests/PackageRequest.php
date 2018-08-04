<?php

namespace Adtech\Core\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
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
                    'package_id' => 'required'
                ];
            }
            case 'POST': {
                return [
                    'domain_id' => 'required',
                    'package' => 'required',//|unique:adtech_core_packages,package
                    'module' => 'required',
                    'space' => 'required'
                ];
            }
            case 'PUT':{
                return [
                    'package_id' => 'required',
                    'package' => 'required',
                    'module' => 'required',
                    'space' => 'required'
                ];
            }
            case 'PATCH':
            default:
                break;
        }
    }
}
