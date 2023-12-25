<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SwitchLangController extends Controller
{
    public function Index($lang)
    {
        session()->put('locale', $lang);
        return back();
    }
}
