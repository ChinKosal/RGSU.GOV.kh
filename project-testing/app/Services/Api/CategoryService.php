<?php
namespace App\Services\Api;
use Illuminate\Support\Facades\Log;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\News;
use App\Http\Resources\Api\CategoriesResource;
use App\Http\Resources\Api\NewsResourceCollection;
use Exception;
class CategoryService{
    private $secureService;
    public function __construct(GetSecureDataService $secureService)
    {
        $this->secureService = $secureService;
    }
    public function getActiveCategories()
    {
        Log::info("Start CategoryService > getActiveCategories | payload: ");
        try {
            $categories = Category:: where('status', 1)->orderByDesc('id')->get();
            
            $categoryResource = new CategoriesResource($categories);
            $dataEncrypt = $this->secureService->getEncryptData($categoryResource);

            return responseSuccess($dataEncrypt);
        } catch (Exception $e) {
            Log::error("Error CategoryService > getActiveCategories | message: ". $e->getMessage());
            return responseError($e->getMessage());
        }
    }
    public function getNewsById(Request $request)
    {
        Log::info("Start CategoryService > getNewsById | payload: ". $request);
        try{
            $dataDecrypt = $this->secureService->getDecryptData($request->data);

            $news = News::where('category_id',(int)$dataDecrypt->id)->paginate(20);

            $newsCollection = new NewsResourceCollection($news);

            $dataEncrypt = $this->secureService->getEncryptData($newsCollection);
            return responseSuccess($dataEncrypt);
        }catch (Exception $e) {
            Log::error("Error NewsService > getNewsById | message: ". $e->getMessage());
            return responseError($e->getMessage());
        }
    }
}