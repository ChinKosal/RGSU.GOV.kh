<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\WebsiteBaseController;

class CommitteeController extends WebsiteBaseController
{
    protected $layout = 'website::pages.committee.';

    public function onIndex($category, $type)
    {
        try {
            $isTypeMemberList       = $type == config('dummy.committee.member_list.url');
            $isTypeTermOfReference  = $type == config('dummy.committee.term_of_reference.url');
            $type == config('dummy.committee.member_list.url') ? $currentType = config('dummy.committee.member_list.key') : null;
            $type == config('dummy.committee.term_of_reference.url') ? $currentType = config('dummy.committee.term_of_reference.key') : null;

            $data['member_list'] = null;
            $data['term_of_reference'] = null;

            if ($isTypeMemberList) {
                $data['member_list'] = $this->websiteService->queryCommitteeMultipleRecords($category, config('dummy.committee.member_list.key'));

                return view($this->layout . 'member', $data);
            }

            if ($isTypeTermOfReference) {
                $data['term_of_reference'] = $this->websiteService->queryCommitteeSingleRecords($category, config('dummy.committee.term_of_reference.key'));

                return view($this->layout . 'term', $data);
            }

            return abort(404, $this->abort);

        } catch (\Exception $e) {
            return abort(403, $this->abort);
        }
    }
}
