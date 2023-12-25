<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReadNewController extends Controller
{
    //
    public function speeches()
    {
        return view('website::components.speeches');
    }
}
