<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DocumentRequest;
use App\Models\Document;
use App\Services\QueryService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    protected $active, $disabled, $model;
    public function __construct()
    {
        $this->middleware('permission:document-view', ['only' => ['index', 'data', 'page']]);
        $this->middleware('permission:document-create', ['only' => ['onStore']]);
        $this->middleware('permission:document-update', ['only' => ['bulkHideShow', 'saveSingleOption']]);
        $this->middleware('permission:document-delete', ['only' => ['onRestore','onDelete','onDestroy']]);

        $this->active   = config('dummy.status.active.key');
        $this->disabled = config('dummy.status.disabled.key');
        $this->model    = Document::class;
    }

    public function index()
    {
        return view("admin::pages.document.index");
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
            ->whereType(request('type'))
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

        foreach ($data as $key => $value) {
            $value->category = $value?->categories() ?? null;
        }

        return response()->json($data);
    }

    public function onStore(DocumentRequest $request)
    {
        $items = [
            'title'         => json_encode([
                'en' => $request->title_en ?? '',
                'km' => $request->title_km ?? '',
            ]),
            'slug'          => $this->convertSlug($request->title_en ?? $request->title_km, $request->id),
            'type'          => $request->type,
            'category'      => $this->jsonArray($request->category),
            'year'          => $request->year,
            'file'          => $request->file,
            'status'        => $request->status,
            'user_id'       => auth('admin')->user()->id,
        ];

        try {
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
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => $e->getMessage(),
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

    public function jsonArray($data)
    {
        if(!is_array($data)) return null;
        $array = [];
        foreach ($data as $key => $value) {
            $array[] = $value['_id'];
        }
        return json_encode($array);
    }
}
