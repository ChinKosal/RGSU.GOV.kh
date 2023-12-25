<?php

namespace App\Services\Api;

use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserDeviceService
{
    private $secureService;
    public function __construct(GetSecureDataService $secureService)
    {
        $this->secureService = $secureService;
    }

    public function storeUserDevice(Request $request)
    {
        Log::info("Start UserDeviceService > storeUserDevice | payload: ". $request);
        try {
            $secretKey = config('encrypt.secret_key');
            $secretIv = config('encrypt.secret_iv');
            $data = [
                'secret_key' => $secretKey,
                'secret_iv' => $secretIv,
            ];

            $userDevice = UserDevice::where('device_id', $request->device_id)
                ->where('device_name', $request->device_name)
                ->where('model', $request->model)
                ->where('os', $request->os)->first();
            if ($userDevice) return responseSuccess($data);

            $validator = Validator::make($request->all(), [
                'device_id' => 'required|unique:user_devices',
                'device_name' => 'required',
                'model' => 'required',
                'os' => 'required',
            ], [
                'device_id.required' => 'device_id required',
                'device_id.unique' => 'device_id already exist',
                'device_name.required' => 'device_name required',
                'model.required' => 'model required',
                'os.required' => 'os required',
            ]);
    
            if ($validator->fails()) {
                return $validator->errors();
            }
            UserDevice::create([
                'device_id' => $request->device_id,
                'device_name' => $request->device_name,
                'model' => $request->model,
                'os' => $request->os,
            ]);
            return responseSuccess($data);
        } catch (\Exception $e) {
            Log::error("Error UserDeviceService > storeUserDevice | message: ". $e->getMessage());
            return responseError($e->getMessage());
        }
    }
}