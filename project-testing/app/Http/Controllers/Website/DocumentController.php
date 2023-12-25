<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\WebsiteBaseController;
class DocumentController extends WebsiteBaseController
{
    protected $layout = 'website::pages.document.';

    public function onCCCDocument($category)
    {
        try{
            $data['documents'] = $this->websiteService->queryDocumentMultipleRecords($category, config('dummy.category.ccc_document.key'),null, 30);
            return view($this->layout . 'ccc-document.index', $data);
        }catch (\Exception $e) {
            return abort(403, $this->abort);
        }
    }

    public function onMeeting($category)
    {
        try{
            $data['years'] = $this->websiteService->queryOnlyYearDocumentMultipleRecords($category, config('dummy.category.meeting_minute.key'));

            return view($this->layout . 'meeting-minute.index', $data);
        }catch (\Exception $e) {
            return abort(403, $this->abort);
        }
    }

    public function onMeetingQueryByYear()
    {
        try{
            $data = $this->websiteService->queryDocumentMultipleRecords(request('category'), request('type'),request('year'));
            return response()->json($data);
        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function onReport($category)
    {
        try{
            $data['documents'] = $this->websiteService->queryDocumentMultipleRecords($category, config('dummy.category.report.key'),null, 30);
            return view($this->layout . 'report.index', $data);
        }catch (\Exception $e) {
            return abort(403, $this->abort);
        }
    }

}
