<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AboutUsRequest;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Category;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    protected $active;
    public function __construct()
    {
        $this->middleware('permission:setting-view', ['only' => ['onPage', 'getData']]);
        $this->middleware('permission:about-us-view', ['only' => ['onAboutUs']]);
        $this->middleware('permission:about-us-create', ['only' => ['onStore','onRowDataSave']]);
        $this->middleware('permission:about-us-update', ['only' => ['onUpdateStatus']]);
        $this->middleware('permission:about-us-delete', ['only' => ['onDelete','onRestore','onDestroy']]);

        $this->active = config('dummy.status.active.key');
    }

    public function onPage($page)
    {
        if($page == 'contact-us') return view('admin::pages.page.contact');
        if ($page == 'app-social-media') return view('admin::pages.page.app-social-media');
        return view('admin::pages.page.show', ['page' => $page]);
    }

    public function getData($page)
    {
        $model = Page::wherePage($page)->first();
        return response()->json($model);
    }

    public function onSave(PageRequest $request)
    {
        $privacyPolicyContent = [
            'en'    => $request->content,
        ];
        $content = $request->page == 'privacy-policy' ? $privacyPolicyContent : [
            'km'    => $request->content_km,
            'en'    => $request->content_en,
        ];


        $items = [
            'page'          => $request->page,
            'content'       => json_encode($content),
            'status'        => $request->status,
            'user_id'       => auth()->id(),
        ];

        DB::beginTransaction();
        try {

            Page::updateOrCreate(['page' => $request->page], $items);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Update success!',
                'error' => false,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
                'error' => true,
            ]);
        }
    }

    public function onSaveContact(Request $request)
    {
        $appSocialMediaContent = [
            'facebook'      => $request->facebook,
            'instagram'     => $request->instagram,
            'youtube'       => $request->youtube,
            'twitter'       => $request->twitter,
            'telegram'      => $request->telegram,
            'website'       => $request->website,
        ];

        $contactUsContent = [
            'email'         => $request->email,
            'phone'         => $request->phone,
            'footer_km'     => $request->footer_km,
            'footer_en'     => $request->footer_en,
            'address_km'    => $request->address_km,
            'address_en'    => $request->address_en,
            'donation'      => $request->donation,
            'telegram_chat' => $request->telegram_chat,
            'embed_map'     => $request->embed_map,
        ];

        $content = $request->page == 'contact-us' ? $contactUsContent : $appSocialMediaContent;

        $items = [
            'page'          => $request->page,
            'content'       => json_encode($content),
            'status'        => $request->status,
            'user_id'       => auth()->id(),
        ];

        DB::beginTransaction();
        try {

            Page::updateOrCreate(['page' => $request->page], $items);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Update success!',
                'error' => false,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
                'error' => true,
            ]);
        }
    }

    // all method below is not used in about us feature
    protected $layout = 'admin::pages.about-us.';

    public function onAboutUs()
    {
        return view($this->layout . 'page');
    }

    public function data()
    {
        $data = Page::query()
                    ->wherePage(request()->page)
                    ->first();

        return response()->json($data);
    }

    public function onStore(AboutUsRequest $request)
    {
        $items = [
            'page'          => $request->page,
            'title'         => json_encode([
                'km'    => $request->title_km,
                'en'    => $request->title_en,
            ]),
            'content'       => json_encode([
                'km'        => $request->content_km,
                'en'        => $request->content_en,
                'gallery'   => $this->jsonArray($request->gallery),
            ]),
            'status'        => $request->status,
            'user_id'       => auth()->id(),
        ];

        DB::beginTransaction();
        try {
            Page::updateOrCreate(['page' => request()->page], $items);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Success!',
                'error' => false,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
                'error' => true,
            ]);
        }
    }

    public function getRowData()
    {
        $data = Page::when(filled(request('search')), function ($q) {
            $q->where(function ($q) {
                $q->where('content->km', 'like', '%' . request('search') . '%')
                    ->orWhere('content->en', 'like', '%' . request('search') . '%');
            });
        })
        ->when(request('page'), function ($q) {
            $q->wherePage(request('page'));
        })
        ->when(request('trash'), function($q) {
            $q->onlyTrashed();
        })
        ->orderByDesc('id')
        ->paginate(25);

        return response()->json($data);
    }

    public function onRowDataSave(AboutUsRequest $request)
    {
        $title = json_encode([
            'km' => $request->title_km,
            'en' => $request->title_en,
        ]);

        $items = [
            'page'      => $request->page,
            'content'   => json_encode([
                'title' => $title,
                'file'  => $request->file,
            ]),
            'status'    => $request->status,
            'user_id'   => auth()->id(),
        ];
        try {
            if (!$request->id) {
                Page::create($items);
            } else {
                Page::whereId($request->id)->update($items);
            }
            return response()->json([
                'status' => 'success',
                'error' => false,
                'message' => $request->id ? __('form.message.update.success') :  __('form.message.create.success'),
            ]);

        } catch (\Exception $e) {
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
            $user = Page::find($request->id);
            $user->update(['status' => $request->status]);
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
            Page::find($request->id)->delete();
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
            Page::onlyTrashed()->find($request->id)->restore();
            return response()->json([
                'status' => 'success',
                'message' => 'Restore Success!',
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

    public function onDestroy(Request $request)
    {
        Page::onlyTrashed()->find($request->id)->forceDelete();
        return response()->json([
            'status' => 'success',
            'error' => false,
            'message' => __('form.message.destroy.success'),
        ]);
    }

    public function jsonArray($data)
    {
        if(!is_array($data)) return null;
        return json_encode(collect($data));
    }
}
