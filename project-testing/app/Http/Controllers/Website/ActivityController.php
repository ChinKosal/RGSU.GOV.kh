<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\WebsiteBaseController;
use Illuminate\Http\Request;

class ActivityController extends WebsiteBaseController
{
    protected $layout = 'website::pages.activity.';

    public function onIndex()
    {

        try {
            $data['activities']  = $this->websiteService->queryActivityMultipleRecords();

            return view($this->layout . 'index', $data);
        } catch (\Exception $e) {
            return abort(403, $this->abort);
        }
    }
    public function onDetail($slug)
    {

        try {
            $data['activity_detail']  = $this->websiteService->queryActivitySingleRecord($slug);
            $data['activities']       = $this->websiteService->queryActivityMultipleRecords( [$data['activity_detail']['id']], 10);

            if (!$data['activity_detail']) {
                return abort(403, $this->abort);
            }

            return view($this->layout . 'detail', $data);
        } catch (\Exception $e) {
            return abort(403, $this->abort);
        }
    }
}
