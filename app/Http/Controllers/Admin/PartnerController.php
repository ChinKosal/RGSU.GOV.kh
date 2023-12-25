<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PartnerRequest;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:partner-view', ['only' => ['index', 'data']]);
        $this->middleware('permission:partner-create', ['only' => ['onSave']]);
        $this->middleware('permission:partner-update', ['only' => ['onUpdate', 'onUpdateStatus']]);
    }

    public function index()
    {          
        return view("admin::pages.partner.index");
    }

    public function data()
    {
        $data = Partner::query()
        ->when(filled(request('search')), function ($q) {
            $q->where(function ($q) {
                $q->where('name', 'like', '%' . request('search') . '%');
            });
        })
        ->orderByDesc('id')
        ->paginate(50);

        return response()->json($data);
    }

    public function onSave(PartnerRequest $request)
    {
        DB::beginTransaction();
        try {
            $items = [
                'name'          => $request->name,
                'link'          => $request->link,
                'logo'          => $request->logo,
                'status'        => $request->status,
                'user_id'       => auth('admin')->user()->id,
            ];

            $message = 'Partner created success!';

            if ($request->id) {
                Partner::find($request->id)->update($items);
                $message = 'Partner updated success!';
            }else{
                Partner::create($items);
            }

            DB::commit();
            return response()->json([
                'status'    => 'success',
                'message'   => $message,
                'error'     => false,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
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
            Partner::find($request->id)->update(['status' => $request->status]);
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
            Partner::whereIn('id', $ids)->update(['status' => $option]);
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
            $blog = Partner::findOrFail($req->id);
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