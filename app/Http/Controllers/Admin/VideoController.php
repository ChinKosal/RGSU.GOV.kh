<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VideoRequest;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:video-view', ['only' => ['index', 'data']]);
        $this->middleware('permission:video-create', ['only' => ['onSave']]);
        $this->middleware('permission:video-update', ['only' => ['onUpdate', 'onUpdateStatus']]);
    }

    public function index()
    {
        return view("admin::pages.video.index");
    }

    public function data()
    {
        $data = Video::query()->orderByDesc('id')->paginate(50);

        return response()->json($data);
    }

    public function onSave(VideoRequest $request)
    {
        try {
            $items = [
                'title'         => json_encode([
                    'en'    => $request->title_en,
                    'km'    => $request->title_km,
                ]),
                'full_url'      => $request->full_url,
                'video_id'      => $request->video_id,
                'thumbnail'     => $request->thumbnail,
                'status'        => $request->status,
                'user_id'       => auth('admin')->user()->id,
            ];

            $message = 'Video created success!';

            if ($request->id) {
                Video::find($request->id)->update($items);
                $message = 'Video updated success!';
            }else{
                Video::create($items);
            }

            return response()->json([
                'status'    => 'success',
                'message'   => $message,
                'error'     => false,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Something went wrong!',
                'error'     => true,
            ]);
        }
    }

    public function onUpdateStatus(Request $request)
    {
        try {
            Video::find($request->id)->update(['status' => $request->status]);
            return response()->json([
                'status' => 'success',
                'message' => 'Success!',
                'error' => false,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
                'error' => true,
            ]);
        }
    }

    public function bulkHideShow(Request $request)
    {
        $ids = $request->partnerIds;
        $option = $request->option;
        try {
            Video::whereIn('id', $ids)->update(['status' => $option]);
            return response()->json([
                'status' => 'success',
                'message' => 'Success!',
                'error' => false,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
                'error' => true,
            ]);
        }
    }

    public function saveSingleOption(Request $req)
    {
        try {
            $blog = Video::findOrFail($req->id);
            $blog->update(['status' => $req->option]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' =>'Something went wrong!',
                'error' => true,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Success!',
            'error' => false,
        ]);
    }
}
