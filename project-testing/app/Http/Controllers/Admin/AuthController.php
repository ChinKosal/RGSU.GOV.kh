<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SignInRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    public function login(SignInRequest $request)
    {
        $remember = $request->get('remember');
        if (Auth::guard('admin')->attempt([(filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username') => $request->username, 'password' => $request->password, 'status' => 1], $remember)) {
            
            if (in_array(Auth::guard('admin')->user()->role, ['super_admin', 'admin'])) {
                return request()->returnUrl ? redirect()->to(request()->returnUrl) : redirect()->route('admin-dashboard');
            }
            Auth::guard('admin')->logout();
        } else {
            return Redirect::back()->with('status', false);
        }
    }

    public function signOut()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin-login');
    }
}