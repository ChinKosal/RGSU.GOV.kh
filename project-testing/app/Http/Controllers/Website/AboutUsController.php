<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\WebsiteBaseController;
use Illuminate\Http\Request;

class AboutUsController extends WebsiteBaseController
{
    protected $layout = 'website::pages.about-us.';

    public function onPage($page)
    {
        $currentPage = null;
        $page == config('dummy.about_us_pages.meeting.url') ? $currentPage = config('dummy.about_us_pages.meeting.key') : null;
        $page == config('dummy.about_us_pages.introduction.url') ? $currentPage = config('dummy.about_us_pages.introduction.key') : null;
        $page == config('dummy.about_us_pages.mandate.url') ? $currentPage = config('dummy.about_us_pages.mandate.key') : null;
        $page == config('dummy.about_us_pages.governance.url') ? $currentPage = config('dummy.about_us_pages.governance.key') : null;
        $page == config('dummy.about_us_pages.role_and_responsibility.url') ? $currentPage = config('dummy.about_us_pages.role_and_responsibility.key') : null;
        $page == config('dummy.about_us_pages.core_principle.url') ? $currentPage = config('dummy.about_us_pages.core_principle.key') : null;
        $page == config('dummy.about_us_pages.structure.url') ? $currentPage = config('dummy.about_us_pages.structure.key') : null;
        $page == config('dummy.about_us_pages.office_bearers.url') ? $currentPage = config('dummy.about_us_pages.office_bearers.key') : null;
        $page == config('dummy.about_us_pages.secretariat.url') ? $currentPage = config('dummy.about_us_pages.secretariat.key') : null;
        $page == config('dummy.about_us_pages.membership.url') ? $currentPage = config('dummy.about_us_pages.membership.key') : null;
        $page == config('dummy.about_us_pages.right_of_member.url') ? $currentPage = config('dummy.about_us_pages.right_of_member.key') : null;
        $page == config('dummy.about_us_pages.responsibility_of_member.url') ? $currentPage = config('dummy.about_us_pages.responsibility_of_member.key') : null;

        // ddd($currentPage);

        try {
            $data['data'] = $this->websiteService->queryPageSingleRecord($currentPage);
            return view($this->layout . 'page', $data);
        } catch (\Exception $e) {
            return abort(403, $this->abort);
        }
    }
}
