<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsRequest;
use App\Models\News;
use App\Services\QueryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    protected $active, $disabled, $model;
    public function __construct()
    {
        $this->middleware('permission:news-view', ['only' => ['index', 'data']]);
        $this->middleware('permission:news-create', ['only' => ['onStore']]);
        $this->middleware('permission:news-update', ['only' => ['bulkHideShow', 'saveSingleOption']]);
        $this->middleware('permission:news-delete', ['only' => ['onRestore','onDelete','onDestroy']]);

        $this->active   = config('dummy.status.active.key');
        $this->disabled = config('dummy.status.disabled.key');
        $this->model    = News::class;
    }

    public function index()
    {
        return view("admin::pages.news.index");
    }

    public function data()
    {
        $data = $this->model::query()
            ->when(filled(request('search')), function ($q) {
                $q->where(function ($q) {
                    $q->where('title->en', 'like', '%' . request('search') . '%')
                        ->orWhere('title->km', 'like', '%' . request('search') . '%');
                });
            })
            ->when(request('trash'), function($q) {
                $q->onlyTrashed();
            })
            ->when(request('status'), function ($q) {
                $q->where('status', $this->disabled);
            })
            ->when(!request('status'), function ($q) {
                if (request('trash')) {
                    $q->onlyTrashed();
                }else{
                    $q->where('status', $this->active);
                }
            })
            ->orderByDesc('id')
            ->paginate(25);

        return response()->json($data);
    }

    public function onStore(NewsRequest $request)
    {
        $items = [
            'title'         => json_encode([
                'en' => $request->title_en,
                'km' => $request->title_km,
            ]),
            'slug'          => $this->convertSlug($request->title_en ?? $request->title_km, $request->id),
            'content'       => json_encode([
                'en' => $request->content_en,
                'km' => $request->content_km,
            ]),
            'thumbnail'     => $request->thumbnail,
            'status'        => $request->status,
            'user_id'       => auth('admin')->user()->id,
        ];

        DB::beginTransaction();
        try {
            DB::commit();
            if (!$request->id) {
                $this->model::create($items);
            } else {
                $data = $this->model::find($request->id);
                $data->update($items);
            }

            return response()->json([
                'status' => 'success',
                'error' => false,
                'message' => $request->id ? __('form.message.update.success') :  __('form.message.create.success'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => __('form.message.error'),
            ]);
        }
    }

    public function bulkHideShow(Request $request)
    {
        $ids = $request->Ids;
        $option = $request->option;
        try {
            $this->model::whereIn('id', $ids)->update(['status' => $option]);
            return response()->json([
                'status' => 'success',
                'message' => __('form.message.status.success'),
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

    public function saveSingleOption(Request $req)
    {
        try {
            $this->model::findOrFail($req->id)->update(['status' => $req->option]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('form.message.error'),
                'error' => true,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('form.message.status.success'),
            'error' => false,
        ]);
    }

    public function onRestore(Request $request)
    {
        $this->model::onlyTrashed()->find($request->id)->restore();
        return response()->json([
            'status' => 'success',
            'error' => false,
            'message' => __('form.message.restore.success'),
        ]);
    }

    public function onDelete(Request $request)
    {
        $this->model::find($request->id)->delete();
        return response()->json([
            'status' => 'success',
            'error' => false,
            'message' => __('form.message.move_to_trash.success'),
        ]);
    }

    public function onDestroy(Request $request)
    {
        $this->model::onlyTrashed()->find($request->id)->forceDelete();
        return response()->json([
            'status' => 'success',
            'error' => false,
            'message' => __('form.message.destroy.success'),
        ]);
    }

    public function convertSlug($slug, $id = null){
        $slug = preg_replace("![^".preg_quote('-')."\pL\pN\s]+!u", '', mb_strtolower($slug));
        $slug = preg_replace("![".preg_quote('-')."\s]+!u", '-', $slug);
        $slug = trim($slug, '-');

        $service = new QueryService();
        $slug = $service->uniqueSlug($this->model::query(),$slug,  $id);

        return $slug;
    }

}
