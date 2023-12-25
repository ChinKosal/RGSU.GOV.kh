<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\Api\GetSecureDataService;
class ViewNewsRequest extends FormRequest
{
    private $secureService;
    public function __construct(GetSecureDataService $secureService)
    {
        $this->secureService = $secureService;
    }
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $dataDecrypt = $this->secureService->getDecryptData(request()->data);
        return [
            'id' => isset($dataDecrypt?->id) ? 'nullable' : 'required',
        ];
    }
    public function messages()
    {
        return [
            'id.required' => 'id is required',
        ];
    }
}
