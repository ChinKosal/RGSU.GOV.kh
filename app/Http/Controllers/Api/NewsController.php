<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Api\GetSecureDataService;
use App\Services\Api\NewsService;
use App\Http\Requests\Api\ViewNewsRequest;
class NewsController extends Controller
{
    private $newsService;
    private $secureService;
    public function __construct(
        NewsService $newsService,
        GetSecureDataService $secureService)
    {
        $this->newsService = $newsService;
        $this->secureService = $secureService;
    }
    public function listActiveNews()
    {
        return $this->newsService->getActiveNews();
    }
    public function viewNews(ViewNewsRequest $request)
    {
        return $this->newsService->getNewsById($request);
    }
    public function homeScreen()
    {
        return $this->newsService->homeScreen();
    }
    function encryptPhp(Request $request) {
        $string = $request->all();
        $dataString = json_encode( $string );
        $encrypt_method="AES-256-CBC";
        $secret_key= config('encrypt.secret_key'); //'MIICXQIBAAKBgQDozl8K7UZ61xB2bxOVCnR9twC1zxoKCS21O0kU8c3lP/7fqboD';
		
      	$secret_iv=  config('encrypt.secret_iv');
      
        //$secret_iv='MIICXQIBAAKBgQDoz';
        $key=hash('SHA512',$secret_key);

        $iv=substr(hash('SHA512',$secret_iv),0,16);
        $output=openssl_encrypt($dataString,$encrypt_method,$key,0,$iv);
        $output=base64_encode($output);
        return $output;
    }
    function decryptPhp(Request $request) {
   
        $mix = base64_decode($request->data);
        $encrypt_method="AES-256-CBC";
        $secret_key= config('encrypt.secret_key'); //'MIICXQIBAAKBgQDozl8K7UZ61xB2bxOVCnR9twC1zxoKCS21O0kU8c3lP/7fqboD';

        $secret_iv=  config('encrypt.secret_iv'); //'MIICXQIBAAKBgQDoz';
        $key=hash('SHA512',$secret_key);

        $iv=substr(hash('SHA512',$secret_iv),0,16);
        $output=openssl_decrypt($mix,$encrypt_method,$key,0,$iv);
       // $output=base64_encode($output);
        return $output;
    }
}
