<?php

namespace App\Http\Controllers;

use App\Services\WebsiteService;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Benchmark;

class WebsiteBaseController extends BaseController
{
    public $abort, $websiteService;
    public function __construct()
    {
        try {
            $this->abort                = 'The page you are looking for could not be found.';
            $this->websiteService       = new WebsiteService();
            $contact                    = $this->websiteService->queryPageSingleRecord(config('dummy.page_type.contact_us'));
            $menuCommittees             = $this->websiteService->queryCategoryMultipleRecords(config('dummy.category.committee.key'));
            $menuCCCDocuments           = $this->websiteService->queryCategoryMultipleRecords(config('dummy.category.ccc_document.key'));
            $menuMeetingMinutes         = $this->websiteService->queryCategoryMultipleRecords(config('dummy.category.meeting_minute.key'));
            $menuReports                = $this->websiteService->queryCategoryMultipleRecords(config('dummy.category.report.key'));
            $menuGfateGrants            = $this->websiteService->queryCategoryMultipleRecords(config('dummy.category.gfatm_grant.key'));
            $menuPrincipalRecipients    = $this->websiteService->queryCategoryMultipleRecords(config('dummy.category.principal_recipient.key'));

            // ddd($menuMeetingMinutes);

            view()->share([
                'contact'               => $contact,
                'menuCommittees'        => $menuCommittees,
                'menuCCCDocuments'      => $menuCCCDocuments,
                'menuMeetingMinutes'    => $menuMeetingMinutes,
                'menuReports'           => $menuReports,
                'menuGfateGrants'       => $menuGfateGrants,
                'menuPrincipals'        => $menuPrincipalRecipients,
            ]);

        } catch (\Exception $e) {
            abort(503);
        }
    }
}
