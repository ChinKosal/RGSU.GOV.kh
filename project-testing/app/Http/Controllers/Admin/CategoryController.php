<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Services\QueryService;
use Illuminate\Support\Facades\DB;
class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:category-view', ['only' => ['index', 'data']]);
        $this->middleware('permission:category-create', ['only' => ['onStore']]);
        $this->middleware('permission:category-update', ['only' => ['onStore', 'onUpdateStatus']]);
        $this->middleware('permission:category-delete', ['only' => ['onDelete','onRestore']]);
    }

    public function index()
    {
        return view("admin::pages.category.index");
    }

    public function data()
    {
        $data = Category::when(filled(request('search')), function ($q) {
            $q->where(function ($q) {
                $q->where('name->km', 'like', '%' . request('search') . '%')
                    ->orWhere('name->en', 'like', '%' . request('search') . '%');
            });
        })
        ->when(request('parent_id'), function($q) {
            $q->where('parent_id', request('parent_id'));
        })
        ->when(request('type'), function ($q) {
            $q->whereType(request('type'));
        })
        ->when(!request('parent_id'), function($q) {
            $q->whereNull('parent_id');
        })
        ->when(request('trash'), function($q) {
            $q->onlyTrashed();
        })
        ->orderBy('ordering')
        ->paginate(25);

        return response()->json($data);
    }

    public function onOrdering()
    {
        $ordering = Category::query()
                        ->when(request('parent_id'), function($q) {
                            $q->where('parent_id', request('parent_id'));
                        })
                        ->when(request('type'), function ($q) {
                            $q->whereType(request('type'));
                        })
                        ->orderByDesc('ordering')
                        ->pluck('ordering')
                        ->first();

        $data = $ordering ? $ordering + 1 : 1;

        return response()->json($data);
    }

    public function onStore(CategoryRequest $request)
    {
        $items = [
            'parent_id' => $request->parent_id ?? null,
            'name'      => json_encode([
                'en' => $request->name_en,
                'km' => $request->name_km,
            ]),
            'slug'      => $this->convertSlug($request->name_en ?? $request->name_km, $request->id),
            'type'      => $request->type,
            'ordering'  => $request->ordering,
            'user_id'   => auth('admin')->user()->id,
            'status'    => $request->status,
        ];
        DB::beginTransaction();
        try {
            if (!$request->id) {
                Category::create($items);
            } else {
                $category = Category::find($request->id);
                $category->update($items);
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => $request->id ? 'Updated Success!' :  'Created Success!',
                'error' => false,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => __('form.message.error'),
                'error' => true,
            ]);
        }
    }

    public function onUpdateStatus(Request $request)
    {
        try {
            Category::find($request->id)->update(['status' => $request->status]);
            return response()->json([
                'status' => 'success',
                'message' => 'Success!',
                'error' => false,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('form.message.error'),
                'error' => true,
            ]);
        }
    }

    public function onDelete(Request $request)
    {
        try {
            Category::find($request->id)->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Deleted Success!',
                'error' => false,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('form.message.error'),
                'error' => true,
            ]);
        }
    }

    public function onRestore(Request $request)
    {
        try {
            Category::onlyTrashed()->find($request->id)->restore();
            return response()->json([
                'status' => 'success',
                'message' => 'Restored Success!',
                'error' => false,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('form.message.error'),
                'error' => true,
            ]);
        }
    }

    public function convertSlug($slug, $id = null){
        $slug = preg_replace("![^".preg_quote('-')."\pL\pN\s]+!u", '', mb_strtolower($slug));
        $slug = preg_replace("![".preg_quote('-')."\s]+!u", '-', $slug);
        $slug = trim($slug, '-');

        $service = new QueryService();
        $slug = $service->uniqueSlug(Category::query(),$slug,  $id);

        return $slug;
    }
}
