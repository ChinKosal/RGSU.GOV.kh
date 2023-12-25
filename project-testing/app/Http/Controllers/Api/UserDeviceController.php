<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Api\UserDeviceService;
class UserDeviceController extends Controller
{
    private $userDeviceService;
    public function __construct(UserDeviceService $userDeviceService)
    {
        $this->userDeviceService = $userDeviceService;
    }

    public function authorization(Request $request)
    {       
        return $this->userDeviceService->storeUserDevice($request);
    }
}
