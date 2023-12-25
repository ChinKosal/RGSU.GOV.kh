<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GalleryRequest;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    protected $active, $disabled, $model;
    public function __construct()
    {
        $this->middleware('permission:gallery-view', ['only' => ['index', 'data']]);
        $this->middleware('permission:gallery-create', ['only' => ['onStore']]);

        $this->active   = config('dummy.status.active.key');
        $this->disabled = config('dummy.status.disabled.key');
        $this->model    = Gallery::class;
    }

    public function index()
    {
        return view("admin::pages.gallery.store");
    }

    public function data()
    {
        $data = $this->model::query()->first();
        return response()->json($data);
    }

    public function onStore(GalleryRequest $request)
    {
        $items = [
            'gallery'       => $this->jsonArray($request->gallery),
            'status'        => $request->status,
        ];

        try {
            $data = $this->model::query()->first();
            if($data){
                $data->update($items);
            }else{
                $this->model::create($items);
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
                'message' => __('form.message.error'),
            ]);
        }
    }

    public function jsonArray($data)
    {
        if(!is_array($data)) return null;
        return json_encode(collect($data));
    }
}
