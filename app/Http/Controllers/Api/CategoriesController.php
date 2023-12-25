<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Api\GetSecureDataService;
use App\Services\Api\CategoryService;
use App\Http\Requests\Api\ListNewsRequest;
class CategoriesController extends Controller
{
    private $categoryService;
    private $secureService;
    public function __construct(
        CategoryService $categoryService,
        GetSecureDataService $secureService)
    {
        $this->categoryService = $categoryService;
        $this->secureService = $secureService;
    }
    public function listActiveCategories()
    {
        return $this->categoryService->getActiveCategories();
    }
    public function listNewsByCategoryId(ListNewsRequest $request)
    {
        return $this->categoryService->getNewsById($request);
    }
}
