<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\WebsiteBaseController;

class PageController extends WebsiteBaseController
{
    protected $layout = 'website::pages.';

    public function onContactUs()
    {
        try{
            return view($this->layout. 'contact-us');
        }catch (\Exception $e) {
            return abort(403, $this->abort);
        }
    }
}
