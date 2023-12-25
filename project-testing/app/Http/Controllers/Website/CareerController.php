<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\WebsiteBaseController;
use Illuminate\Http\Request;

class CareerController extends WebsiteBaseController
{
    protected $layout = 'website::pages.career.';

    public function onIndex()
    {

        try {
            $data['careers'] = $this->websiteService->queryCareerMultipleRecords(12);
            // ddd($data['careers']);

            return view($this->layout . 'index', $data);
        } catch (\Exception $e) {
            return abort(403, $this->abort);
        }
    }

    public function onDetail($slug)
    {

        try {
            $data['detail']  = $this->websiteService->queryCareerSingleRecord($slug);

            if (!$data['detail']) {
                return abort(403, $this->abort);
            }

            return view($this->layout . 'detail', $data);
        } catch (\Exception $e) {
            return abort(403, $this->abort);
        }
    }
}
