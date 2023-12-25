<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SliderRequest;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    protected $layout = 'admin::pages.slider.';

    public function __construct()
    {
        $this->middleware('permission:slider-view', ['only' => ['index', 'data']]);
        $this->middleware('permission:slider-create', ['only' => ['onSave']]);
        $this->middleware('permission:slider-update', ['only' => ['onUpdate', 'onUpdateStatus']]);
    }

    public function index()
    {
        return view($this->layout . 'index');
    }

    public function data()
    {
        $data = Slider::query()
                    ->orderBy('ordering')
                    ->paginate(50);

        return response()->json($data);
    }

    public function onStore(SliderRequest $request)
    {
        try {
            $items = [
                'thumbnail' => $request->thumbnail,
                'link'      => $request->link,
                'ordering'  => $request->ordering,
                'content'       => json_encode([
                    'en' => $request->content_en,
                    'km' => $request->content_km,
                ]),
                'status'    => $request->status,
                'user_id'   => auth('admin')->user()->id,
            ];


            if (!$request->id) {
                Slider::create($items);
            } else {
                Slider::find($request->id)->update($items);
            }

            return response()->json([
                'error'    => false,
                'message'   => 'Create slider successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error'    => true,
                'message'   => 'Something went wrong',
            ]);
        }
    }


    public function getOrdering()
    {
        $ordering = Slider::query()
                        ->orderByDesc('ordering')
                        ->pluck('ordering')
                        ->first();

        $data = $ordering ? $ordering + 1 : 1;

        return response()->json($data);
    }

    public function onUpdateStatus(Request $request)
    {
        try {
            $slider = Slider::find($request->id);
            $slider->update([
                'status' => $request->status,
            ]);

            return response()->json([
                'error'    => false,
                'message'   => 'Update status successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error'    => true,
                'message'   => 'Something went wrong',
            ]);
        }
    }
}
