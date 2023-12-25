<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\WebsiteBaseController;

class HomeController extends WebsiteBaseController
{
    public function onHome()
    {
        try {
            // $data['sliders']    = $this->websiteService->querySliderMultipleRecords();
            // $data['about_us']   = $this->websiteService->queryPageSingleRecord(config('dummy.about_us_pages.about_us.key'));
            // $data['news']       = $this->websiteService->queryNewsMultipleRecords([], 6);
            // $data['videos']     = $this->websiteService->queryVideoMultipleRecords(3);
            // $data['partners']   = $this->websiteService->queryPartnerMultipleRecords();

            return view('website::pages.index');
        } catch (\Exception $e) {
            return abort(403, $this->abort);
        }
    }
}
