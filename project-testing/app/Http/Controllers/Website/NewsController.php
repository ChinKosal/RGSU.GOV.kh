<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\WebsiteBaseController;
use Illuminate\Http\Request;

class NewsController extends WebsiteBaseController
{
    protected $layout = 'website::pages.media.';

    public function onIndex()
    {
        try {
            $data['news']  = $this->websiteService->queryNewsMultipleRecords([], 12);

            return view($this->layout . 'news.index', $data);
        } catch (\Exception $e) {
            return abort(403, $this->abort);
        }
    }

    public function onDetail($slug)
    {
        try {
            $data['detail']  = $this->websiteService->queryNewsSingleRecord($slug);
            $data['news']   = $this->websiteService->queryNewsMultipleRecords( [$data['detail']['id']], 10);
            
            if (!$data['detail']) {
                return abort(403, $this->abort);
            }
            
            return view($this->layout . 'news.detail', $data);
        } catch (\Exception $e) {
            return abort(403, $this->abort);
        }
    }
}
