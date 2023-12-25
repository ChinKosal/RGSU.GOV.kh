<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Page6Controller extends Controller
{
    //
    public function PM()
    {
        return view('website::pages.Pmpage.view');
    }
}
