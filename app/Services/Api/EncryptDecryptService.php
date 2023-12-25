<?php

namespace App\Services\Api;

class EncryptDecryptService
{
    public function secureEncryptV1($data)
    {
        $dataString = json_encode($data);
        $firstKey = config('encrypt.first_secret_key');
        $secondKey = config('encrypt.second_secret_key');    
            
        $method = "aes-256-cbc";    
        $ivLength = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($ivLength);

        $firstEncrypted = openssl_encrypt($dataString, $method, $firstKey, OPENSSL_RAW_DATA, $iv);    
        $secondEncrypted = hash_hmac('sha3-512', $firstEncrypted, $secondKey, TRUE);                
        $output = base64_encode($iv.$secondEncrypted.$firstEncrypted);  
        
        return $output;     
    }

    public function secureDecryptV1($data)
    {
        $firstKey = config('encrypt.first_secret_key');
        $secondKey = config('encrypt.second_secret_key');              
        $mix = base64_decode($data);
                
        $method = "aes-256-cbc";    
        $ivLength = openssl_cipher_iv_length($method);
                    
        $iv = substr($mix, 0, $ivLength);
        $firstEncrypted = substr($mix, $ivLength + 64);
        $secondEncrypted = substr($mix, $ivLength, 64);
        
        $data = openssl_decrypt($firstEncrypted, $method, $firstKey, OPENSSL_RAW_DATA, $iv);
        $secondEncryptedNew = hash_hmac('sha3-512', $firstEncrypted, $secondKey, TRUE);
            
        if (hash_equals($secondEncrypted, $secondEncryptedNew)) return $data;
            
        return false;
    }

    public function secureEncrypt($data) 
    {
      	$dataString = json_encode($data);
        $encryptMethod = "AES-256-CBC";
        $secretKey = config('encrypt.secret_key');
      	$secretIv = config('encrypt.secret_iv');
      
        $key = hash('SHA512', $secretKey);
        $iv = substr(hash('SHA512', $secretIv), 0, 16);
        $output = openssl_encrypt($dataString, $encryptMethod, $key, 0, $iv);
        $output = base64_encode($output);

        return $output;
    }

    public function secureDecrypt($data) 
    {
        $mix = base64_decode($data);
        $encryptMethod = "AES-256-CBC";
        $secretKey = config('encrypt.secret_key'); 
        $secretIv = config('encrypt.secret_iv');

        $key = hash('SHA512', $secretKey);
        $iv = substr(hash('SHA512', $secretIv),0,16);
        $output = openssl_decrypt($mix, $encryptMethod, $key, 0, $iv);

        return $output;
    }
}