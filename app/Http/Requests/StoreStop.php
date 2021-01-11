<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Code;

class StoreStop extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $code_id = Code::find($this['code_id'])->code;

        switch($code_id) {
            case 0:
                return [
                    'code_id' => 'exists:codes,id',
                    'machine_id' => 'exists:machines,id|required',
                    'product_id' => 'exists:products,id|required',
                    'color_id' => 'exists:colors,id|required',
                ];
            break;
            case 6:
            case 7:
            case 8:
            case 9:
            case 10:
            case 11:
                return [
                    'code_id' => 'exists:codes,id',
                    'machine_id' => 'exists:machines,id|required',
                    'comment' => 'required'
                ];
            break;
            default:
                return [
                    'code_id' => 'exists:codes,id',
                    'machine_id' => 'exists:machines,id|required',
                ];
            break;
        }
    }

    public function messages()
    {
        return [

        ];
    }
}
