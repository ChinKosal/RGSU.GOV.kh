<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\WebsiteBaseController;
use Illuminate\Http\Request;

class PrincipalController extends WebsiteBaseController
{
    protected $layout = 'website::pages.principal_recipient.';

    public function onIndex($slug, $category)
    {
        try {

            $pudr              = config('dummy.principal_recipient.pudr.url');
            $management_letter = config('dummy.principal_recipient.management_letter.url');
            $audit_report      = config('dummy.principal_recipient.audit_report.url');
            // ddd($slug);
            if($category == $pudr){
                $data['data']   = $this->websiteService->queryPrincipalMultipleRecords($slug, config('dummy.principal_recipient.pudr.key'));
                return view($this->layout . 'index', $data);
            }
            
            if ($category == $management_letter) {
                $data['content']= $this->websiteService->queryPrincipalSingleRecord($slug, config('dummy.principal_recipient.management_letter.key'));
                return view($this->layout . 'content', $data);
            }
            if ($category == $audit_report) {
                $data['content']= $this->websiteService->queryPrincipalSingleRecord($slug, config('dummy.principal_recipient.audit_report.key'));
                return view($this->layout . 'content', $data);
            }
            
        } catch (\Exception $e) {
            return abort(403, $this->abort);
        }
    }
}
