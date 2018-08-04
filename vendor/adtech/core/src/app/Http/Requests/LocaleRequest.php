<?php

namespace Adtech\Core\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocaleRequest extends FormRequest
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
                    'locale_id' => 'required'
                ];
            }
            case 'POST': {
                return [
                    'name' => 'required',
                    'alias' => 'required|unique:mysql_core.adtech_core_locales,alias'
                ];
            }
            case 'PUT':{
                return [
                    'locale_id' => 'required',
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
