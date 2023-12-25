<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\WebsiteBaseController;

class GrantController extends WebsiteBaseController
{
    protected $layout = 'website::pages.grant.';

    public function onIndex($slug)
    {

        try {
            $data['category'] = $this->websiteService->queryCategorySingleRecord($slug);
            $data['data']     = $this->websiteService->queryGfatmGrantMultipleRecords($slug);

            return view($this->layout . 'index', $data);
        } catch (\Exception $e) {
            return abort(403, $this->abort);
        }
    }
}
