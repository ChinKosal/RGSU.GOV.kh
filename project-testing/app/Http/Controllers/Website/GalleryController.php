<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\WebsiteBaseController;
use Illuminate\Http\Request;

class GalleryController extends WebsiteBaseController
{
    protected $layout = 'website::pages.media.';

    public function onIndex()
    {
        try {
            $data['images'] = $this->websiteService->queryGallerySingleRecord();

            // ddd($data['images']);

            return view($this->layout . 'gallery.index', $data);
        } catch (\Exception $e) {
            return abort(403, $this->abort);
        }
    }
}
