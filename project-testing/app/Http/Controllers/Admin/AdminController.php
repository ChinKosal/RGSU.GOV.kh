<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PasswordRequest;
use App\Http\Requests\Admin\UserRequest;
use App\Models\ModulePermission;
use App\Models\Permission;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin-view', ['only' => ['index']]);
        $this->middleware('permission:admin-create', ['only' => ['onCreate', 'onSave']]);
        $this->middleware('permission:admin-update', ['only' => ['onUpdate', 'onUpdateStatus', 'onDelete', 'onRestore', 'onChangePassword', 'onSavePassword', 'userPermission', 'userPermissionSave']]);
    }

    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('admin-dashboard');
        }
        return view("admin::auth.sign-in");
    }

    public function index()
    {
        return view("admin::pages.admin.index");
    }

    public function data()
    {
        $data = User::when(filled(request('search')), function ($q) {
            /**
             * Filter by search
             */
            $q->where(function ($q) {
                $q->where('username', 'like', '%' . request('search') . '%')
                    ->orWhere('phone', 'like', '%' . request('search') . '%')
                    ->orWhere('email', 'like', '%' . request('search') . '%');
            });
        })
            /**
             * Show only trash data
             */
            ->when(request('trash'), function ($q) {
                $q->onlyTrashed();
            })
            ->where('role', config('dummy.user.role.admin'))
            ->orderByDesc("created_at")
            ->paginate(25);

        return response()->json($data);
    }

    public function onSave(UserRequest $request)
    {
        $items = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
            'password' => bcrypt($request->password),
            'avatar' =>  $request->avatar,
            'remember_token' => $request->_token,
            'role' => config('dummy.user.role.admin'),
        ];

        try {
            User::create($items);
            return response()->json([
                'status' => 'success',
                'message' => __('user.message.create.success'),
                'error' => false,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('user.message.error'),
                'error' => true,
            ]);
        }
    }

    public function onUpdate(UserRequest $request)
    {
        $items = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
            'avatar' => $request->avatar,
        ];

        try {
            User::find($request->id)->update($items);
            return response()->json([
                'status' => 'success',
                'message' => __('user.message.update.success'),
                'error' => false,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('user.message.error'),
                'error' => true,
            ]);
        }
    }

    public function onSavePassword(PasswordRequest $request)
    {
        try {
            $user = User::find($request->id);
            $user->update(['password' => bcrypt($request->new_password)]);
            return response()->json([
                'status' => 'success',
                'message' => __('user.message.change_password'),
                'error' => false,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('user.message.error'),
                'error' => true,
            ]);
        }
    }

    public function onUpdateStatus(Request $request)
    {
        try {
            $user = User::find($request->id);
            $user->update(['status' => $request->status]);
            return response()->json([
                'status' => 'success',
                'message' => __('user.message.status.success'),
                'error' => false,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('user.message.error'),
                'error' => true,
            ]);
        }
    }

    public function onDelete(Request $request)
    {
        try {
            User::find($request->id)->delete();
            return response()->json([
                'status' => 'success',
                'message' => __('user.message.delete.success'),
                'error' => false,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('user.message.error'),
                'error' => true,
            ]);
        }
    }

    public function onRestore(Request $request)
    {
        try {
            User::onlyTrashed()->find($request->id)->restore();
            return response()->json([
                'status' => 'success',
                'message' => __('user.message.restore.success'),
                'error' => false,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('user.message.error'),
                'error' => true,
            ]);
        }
    }
    //Permission
    public function userPermission(Request $request)
    {
        $data['user'] = User::find($request->id);
        $data['ModulePermission'] = ModulePermission::with('permission')->orderBy('sort_no')->get();
        if (isset($data['ModulePermission']) && count($data['ModulePermission']) > 0)
            foreach ($data['ModulePermission'] as $module) {
                $module->check = false;
                if (isset($module->permission) && count($module->permission) > 0) {
                    foreach ($module->permission as $perItem)
                        if (in_array($perItem->id, $data['user']->ModelHasPermission->pluck('permission_id')->toArray())) {
                            $perItem->check = true;
                            $module->check = true;
                        } else {
                            $perItem->check = false;
                        }
                }
            }
        return response()->json($data);

    }
    public function userPermissionSave(Request $req)
    {
        try {
            $data = User::find($req->id);
            $this->savePermission($req->permission, $data);
            return response()->json([
                'status' => 'success',
                'message' => __('user.message.update.success'),
                'error' => false,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('user.message.error'),
                'error' => true,
            ]);
        }
    }
    public function savePermission($permission, $user)
    {
        $permissions = Permission::pluck('name')->toArray();
        $revoke = array_diff($permissions, $permission);
        $user->givePermissionTo($permission);
        $user->revokePermissionTo($revoke);
    }
}
