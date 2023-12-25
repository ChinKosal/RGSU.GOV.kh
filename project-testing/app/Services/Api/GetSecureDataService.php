<?php

namespace App\Services\Api;

class GetSecureDataService
{
    private $encryptDecryptService;
    function __construct(EncryptDecryptService $encryptDecryptService)
    {
        $this->encryptDecryptService = $encryptDecryptService;
    }

    public function getEncryptData($decryptData)
    {
        $dataEncrypt = $this->encryptDecryptService->secureEncrypt($decryptData);
        return $dataEncrypt;
    }

    public function getDecryptData($encryptData)
    {
        $decrypt = $this->encryptDecryptService->secureDecrypt($encryptData);
        $dataDecrypt = json_decode($decrypt);
        
        return $dataDecrypt;
    }
}