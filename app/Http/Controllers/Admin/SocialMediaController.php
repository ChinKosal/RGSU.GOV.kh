<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SocialMediaRequest;
use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:social-media-view', ['only' => ['index', 'data']]);
        $this->middleware('permission:social-media-create', ['only' => ['onSave']]);
        $this->middleware('permission:social-media-update', ['only' => ['onUpdate', 'onUpdateStatus']]);
    }

    public function index()
    {
        return view("admin::pages.social-media.index");
    }

    public function data()
    {
        $data = SocialMedia::query()
                ->whereType(request()->type)
                ->when(request()->search, function ($query) {
                    $query->where('name', 'like', '%' . request()->search . '%');
                })
                ->orderByDesc('id')
                ->paginate(50);

        return response()->json($data);
    }

    public function onSave(SocialMediaRequest $request)
    {
        try {
            $items = [
                'name'          => $request->name,
                'link'          => $request->link,
                'icon'          => $request->icon,
                'type'          => $request->type,
                'status'        => $request->status,
                'user_id'       => auth('admin')->user()->id,
            ];

            $message = 'Social media created success!';

            if ($request->id) {
                SocialMedia::find($request->id)->update($items);
                $message = 'Social media updated success!';
            }else{
                SocialMedia::create($items);
            }

            return response()->json([
                'status'    => 'success',
                'message'   => $message,
                'error'     => false,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage(),
                'error'     => true,
            ]);
        }
    }

    public function onUpdateStatus(Request $request)
    {
        try {
            SocialMedia::find($request->id)->update(['status' => $request->status]);
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
        $ids = $request->Ids;
        $option = $request->option;
        try {
            SocialMedia::whereIn('id', $ids)->update(['status' => $option]);
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
            SocialMedia::findOrFail($req->id)->update(['status' => $req->option]);
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
