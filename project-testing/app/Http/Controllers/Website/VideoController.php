<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\WebsiteBaseController;
use Illuminate\Http\Request;

class VideoController extends WebsiteBaseController
{
    protected $layout = 'website::pages.media.';
    
    public function onIndex()
    {
        try {
            $data['videos']     = $this->websiteService->queryVideoMultipleRecords();

            return view($this->layout . 'videos.index', $data);
        } catch (\Exception $e) {
            return abort(403, $this->abort);
        }
    }
}
