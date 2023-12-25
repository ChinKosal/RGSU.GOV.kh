<?php

namespace App\Services\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\News;
use App\Http\Resources\Api\NewsResourceCollection;
use Exception;
class NewsService
{
    private $secureService;
    public function __construct(GetSecureDataService $secureService)
    {
        $this->secureService = $secureService;
    }
    public function getActiveNews()
    {
        Log::info("Start NewsService > getActiveNews | payload: ");
        try {
            $news = News:: where('status', 1)->orderByDesc('id')->paginate(20);
            
            $newsCollection = new NewsResourceCollection($news);
            $dataEncrypt = $this->secureService->getEncryptData($newsCollection);

            return responseSuccess($dataEncrypt);
        } catch (Exception $e) {
            Log::error("Error NewsService > getActiveNews | message: ". $e->getMessage());
            return responseError($e->getMessage());
        }
    }
    public function getNewsById(Request $request)
    {
        Log::info("Start NewsService > getNewsById | payload: ". $request);
        try{
            $dataDecrypt = $this->secureService->getDecryptData($request->data);

            $news = News::findOrFail((int)$dataDecrypt->id);
            $dataEncrypt = $this->secureService->getEncryptData($news);
            return responseSuccess($dataEncrypt);
        }catch (Exception $e) {
            Log::error("Error NewsService > getNewsById | message: ". $e->getMessage());
            return responseError($e->getMessage());
        }
    }
    public function homeScreen()
    {
        Log::info("Start NewsService > homeScreen | payload: ");
        try {
            $slide = News::orderby('id','desc')->limit(5)->select('id','title','thumbnail')->get();
		    $text = News::orderby('id','desc')->limit(1)->select('id','title')->get();
            $encryptData = [
                'slide' => $slide,
                'runningText' => $text,
            ];

            $dataAfterEncrypt = $this->secureService->getEncryptData($encryptData);

            return responseSuccess($dataAfterEncrypt);
        } catch (Exception $e) {
            Log::error("Error NewsService > getActiveNews | message: ". $e->getMessage());
            return responseError($e->getMessage());
        }
    }
}