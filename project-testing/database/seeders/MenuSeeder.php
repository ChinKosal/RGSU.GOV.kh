<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Menu::truncate();

        // category
        $category = Menu::create([
            'name' => json_encode(['en' => 'Category']),
            'icon' => 'grid',
            'path' => 'admin/category/list',
            'active' => 'admin/category/*',
            'permission' => array('category-view'),
        ]);

        // Category - Committee
        Menu::create([
            'name' => json_encode(['en' => config('dummy.category.committee.text')]),
            'icon' => 'grid',
            'path' => 'admin/category/' . config('dummy.category.committee.url') . '/list' . '?type=' . config('dummy.category.committee.key'),
            'active' => 'admin/category/' . config('dummy.category.committee.url') . '/*',
            'permission' => array('category-view'),
            'parent_id' => $category->id,
        ]);

        // Category - CCC Document
        Menu::create([
            'name' => json_encode(['en' => config('dummy.category.ccc_document.text')]),
            'icon' => 'grid',
            'path' => 'admin/category/' . config('dummy.category.ccc_document.url') . '/list' . '?type=' . config('dummy.category.ccc_document.key'),
            'active' => 'admin/category/' . config('dummy.category.ccc_document.url') . '/*',
            'permission' => array('category-view'),
            'parent_id' => $category->id,
        ]);

        // Category - Meeting Minute
        Menu::create([
            'name' => json_encode(['en' => config('dummy.category.meeting_minute.text')]),
            'icon' => 'grid',
            'path' => 'admin/category/' . config('dummy.category.meeting_minute.url') . '/list' . '?type=' . config('dummy.category.meeting_minute.key'),
            'active' => 'admin/category/' . config('dummy.category.meeting_minute.url') . '/*',
            'permission' => array('category-view'),
            'parent_id' => $category->id,
        ]);

        // Category - Report
        Menu::create([
            'name' => json_encode(['en' => config('dummy.category.report.text')]),
            'icon' => 'grid',
            'path' => 'admin/category/' . config('dummy.category.report.url') . '/list' . '?type=' . config('dummy.category.report.key'),
            'active' => 'admin/category/' . config('dummy.category.report.url') . '/*',
            'permission' => array('category-view'),
            'parent_id' => $category->id,
        ]);

        // Category - GFATM Grant
        Menu::create([
            'name' => json_encode(['en' => config('dummy.category.gfatm_grant.text')]),
            'icon' => 'grid',
            'path' => 'admin/category/' . config('dummy.category.gfatm_grant.url') . '/list' . '?type=' . config('dummy.category.gfatm_grant.key'),
            'active' => 'admin/category/' . config('dummy.category.gfatm_grant.url') . '/*',
            'permission' => array('category-view'),
            'parent_id' => $category->id,
        ]);

        // Category - Principal Recipient
        Menu::create([
            'name' => json_encode(['en' => config('dummy.category.principal_recipient.text')]),
            'icon' => 'grid',
            'path' => 'admin/category/' . config('dummy.category.principal_recipient.url') . '/list' . '?type=' . config('dummy.category.principal_recipient.key'),
            'active' => 'admin/category/' . config('dummy.category.principal_recipient.url') . '/*',
            'permission' => array('category-view'),
            'parent_id' => $category->id,
        ]);

        // Slider
        Menu::create([
            'name' => json_encode(['en' => 'Slider']),
            'icon' => 'image',
            'path' => 'admin/slider/list',
            'active' => 'admin/slider/*',
            'permission' => array('slider-view'),
        ]);

        // Committees
        $committee = Menu::create([
            'name'          => json_encode(['en' => 'Committees']),
            'icon'          => 'clipboard',
            'path'          => 'admin/committee/list',
            'active'        => 'admin/committee/*',
            'permission'    => array('committee-view'),
        ]);

        // Committees - Member list
        Menu::create([
            'parent_id'     => $committee->id,
            'name'          => json_encode(['en' => config('dummy.committee.member_list.text')]),
            'icon'          => 'file-text',
            'path'          => 'admin/committee/' . config('dummy.committee.member_list.url') . '/list' . '?type=' . config('dummy.committee.member_list.key'),
            'active'        => 'admin/committee/' . config('dummy.committee.member_list.url') . '/*',
            'permission'    => array('committee-view'),
        ]);

        // Committees - Term of Reference
        Menu::create([
            'parent_id'     => $committee->id,
            'name'          => json_encode(['en' => config('dummy.committee.term_of_reference.text')]),
            'icon'          => 'file-text',
            'path'          => 'admin/committee/' . config('dummy.committee.term_of_reference.url') . '/list' . '?type=' . config('dummy.committee.term_of_reference.key'),
            'active'        => 'admin/committee/' . config('dummy.committee.term_of_reference.url') . '/*',
            'permission'    => array('committee-view'),
        ]);

        // Document
        $document = Menu::create([
            'name'          => json_encode(['en' => 'Document']),
            'icon'          => 'book-open',
            'path'          => 'admin/document/list',
            'active'        => 'admin/document/*',
            'permission'    => array('document-view'),
        ]);

        // Document - CCC Document
        Menu::create([
            'parent_id'     => $document->id,
            'name'          => json_encode(['en' => config('dummy.category.ccc_document.text')]),
            'icon'          => 'book',
            'path'          => 'admin/document/' . config('dummy.category.ccc_document.url') . '/list' . '?type=' . config('dummy.category.ccc_document.key'),
            'active'        => 'admin/document/' . config('dummy.category.ccc_document.url') . '/*',
            'permission'    => array('document-view'),
        ]);

        // Document - Meeting Minute
        Menu::create([
            'parent_id'     => $document->id,
            'name'          => json_encode(['en' => config('dummy.category.meeting_minute.text')]),
            'icon'          => 'book',
            'path'          => 'admin/document/' . config('dummy.category.meeting_minute.url') . '/list' . '?type=' . config('dummy.category.meeting_minute.key'),
            'active'        => 'admin/document/' . config('dummy.category.meeting_minute.url') . '/*',
            'permission'    => array('document-view'),
        ]);

        // Document - Report
        Menu::create([
            'parent_id'     => $document->id,
            'name'          => json_encode(['en' => config('dummy.category.report.text')]),
            'icon'          => 'book',
            'path'          => 'admin/document/' . config('dummy.category.report.url') . '/list' . '?type=' . config('dummy.category.report.key'),
            'active'        => 'admin/document/' . config('dummy.category.report.url') . '/*',
            'permission'    => array('document-view'),
        ]);

        // GFATM Grant
        Menu::create([
            'name'          => json_encode(['en' => 'GFATM Grant']),
            'icon'          => 'file-text',
            'path'          => 'admin/gfatm-grant/list',
            'active'        => 'admin/gfatm-grant/*',
            'permission'    => array('gfatm-grant-view'),
        ]);

        // Principal Recipient
        $principal = Menu::create([
            'name'          => json_encode(['en' => 'Principal Recipient']),
            'icon'          => 'hexagon',
            'path'          => 'admin/principal-recipient/list',
            'active'        => 'admin/principal-recipient/*',
            'permission'    => array('principal-recipient-view'),
        ]);

        // Principal Recipient - PUDR
        Menu::create([
            'parent_id'     => $principal->id,
            'name'          => json_encode(['en' => config('dummy.principal_recipient.pudr.text')]),
            'icon'          => 'file-text',
            'path'          => 'admin/principal-recipient/' . config('dummy.principal_recipient.pudr.url') . '/list' . '?type=' . config('dummy.principal_recipient.pudr.key'),
            'active'        => 'admin/principal-recipient/' . config('dummy.principal_recipient.pudr.url') . '/*',
            'permission'    => array('principal-recipient-view'),
        ]);

        // Principal Recipient - Management Letter
        Menu::create([
            'parent_id'     => $principal->id,
            'name'          => json_encode(['en' => config('dummy.principal_recipient.management_letter.text')]),
            'icon'          => 'file-text',
            'path'          => 'admin/principal-recipient/' . config('dummy.principal_recipient.management_letter.url') . '/list' . '?type=' . config('dummy.principal_recipient.management_letter.key'),
            'active'        => 'admin/principal-recipient/' . config('dummy.principal_recipient.management_letter.url') . '/*',
            'permission'    => array('principal-recipient-view'),
        ]);

        // Principal Recipient - Audit Report
        Menu::create([
            'parent_id'     => $principal->id,
            'name'          => json_encode(['en' => config('dummy.principal_recipient.audit_report.text')]),
            'icon'          => 'file-text',
            'path'          => 'admin/principal-recipient/' . config('dummy.principal_recipient.audit_report.url') . '/list' . '?type=' . config('dummy.principal_recipient.audit_report.key'),
            'active'        => 'admin/principal-recipient/' . config('dummy.principal_recipient.audit_report.url') . '/*',
            'permission'    => array('principal-recipient-view'),
        ]);

        // News
        Menu::create([
            'name'          => json_encode(['en' => 'News']),
            'icon'          => 'file-text',
            'path'          => 'admin/news/list',
            'active'        => 'admin/news/*',
            'permission'    => array('news-view'),
        ]);

        // Activity
        Menu::create([
            'name'          => json_encode(['en' => 'Activity']),
            'icon'          => 'file-text',
            'path'          => 'admin/activity/list',
            'active'        => 'admin/activity/*',
            'permission'    => array('activity-view'),
        ]);

        // Gallery
        Menu::create([
            'name'          => json_encode(['en' => 'Gallery']),
            'icon'          => 'image',
            'path'          => 'admin/gallery/list',
            'active'        => 'admin/gallery/*',
            'permission'    => array('gallery-view'),
        ]);

        // Video
        Menu::create([
            'name'          => json_encode(['en' => 'Video']),
            'icon'          => 'video',
            'path'          => 'admin/video/list',
            'active'        => 'admin/video/*',
            'permission'    => array('video-view'),
        ]);

        // Career
        Menu::create([
            'name'          => json_encode(['en' => 'Career']),
            'icon'          => 'briefcase',
            'path'          => 'admin/career/list',
            'active'        => 'admin/career/*',
            'permission'    => array('career-view'),
        ]);

        // Partner
        Menu::create([
            'name'          => json_encode(['en' => 'Partner']),
            'icon'          => 'users',
            'path'          => 'admin/partner/list',
            'active'        => 'admin/partner/*',
            'permission'    => array('partner-view'),
        ]);

        // About Us
        $about = Menu::create([
            'name' => json_encode(['en' => 'About Us']),
            'icon' => 'info',
            'path' => 'admin/about-us/list',
            'active' => 'admin/about-us/*',
            'permission' => array('about-us-view'),
        ]);

        // About Us - Content
        Menu::create([
            'parent_id' => $about->id,
            'name' => json_encode(['en' => config('dummy.about_us_pages.about_us.text')]),
            'icon' => 'info',
            'path' => 'admin/about-us/'. config('dummy.about_us_pages.about_us.url').'/list' . '?page=' . config('dummy.about_us_pages.about_us.key'),
            'active' => 'admin/about-us/'. config('dummy.about_us_pages.about_us.url') . '/*',
            'permission' => array('about-us-view'),
        ]);

        // About Us - Meeting
        Menu::create([
            'parent_id' => $about->id,
            'name' => json_encode(['en' => config('dummy.about_us_pages.meeting.text')]),
            'icon' => 'info',
            'path' => 'admin/about-us/'. config('dummy.about_us_pages.meeting.url').'/list' . '?page=' . config('dummy.about_us_pages.meeting.key'),
            'active' => 'admin/about-us/'. config('dummy.about_us_pages.meeting.url') . '/*',
            'permission' => array('about-us-view'),
        ]);

        // About Us - Introduction
        Menu::create([
            'parent_id' => $about->id,
            'name' => json_encode(['en' => config('dummy.about_us_pages.introduction.text')]),
            'icon' => 'info',
            'path' => 'admin/about-us/'. config('dummy.about_us_pages.introduction.url').'/list' . '?page=' . config('dummy.about_us_pages.introduction.key'),
            'active' => 'admin/about-us/'. config('dummy.about_us_pages.introduction.url') . '/*',
            'permission' => array('about-us-view'),
        ]);

        // About Us - Mandate
        Menu::create([
            'parent_id' => $about->id,
            'name' => json_encode(['en' => config('dummy.about_us_pages.mandate.text')]),
            'icon' => 'info',
            'path' => 'admin/about-us/'. config('dummy.about_us_pages.mandate.url').'/list' . '?page=' . config('dummy.about_us_pages.mandate.key'),
            'active' => 'admin/about-us/'. config('dummy.about_us_pages.mandate.url') . '/*',
            'permission' => array('about-us-view'),
        ]);

        // About Us - Governance
        Menu::create([
            'parent_id' => $about->id,
            'name' => json_encode(['en' => config('dummy.about_us_pages.governance.text')]),
            'icon' => 'info',
            'path' => 'admin/about-us/'. config('dummy.about_us_pages.governance.url').'/list' . '?page=' . config('dummy.about_us_pages.governance.key'),
            'active' => 'admin/about-us/'. config('dummy.about_us_pages.governance.url') . '/*',
            'permission' => array('about-us-view'),
        ]);

        // About Us - Role and Responsibility
        Menu::create([
            'parent_id' => $about->id,
            'name' => json_encode(['en' => config('dummy.about_us_pages.role_and_responsibility.text')]),
            'icon' => 'info',
            'path' => 'admin/about-us/'. config('dummy.about_us_pages.role_and_responsibility.url').'/list' . '?page=' . config('dummy.about_us_pages.role_and_responsibility.key'),
            'active' => 'admin/about-us/'. config('dummy.about_us_pages.role_and_responsibility.url') . '/*',
            'permission' => array('about-us-view'),
        ]);

        // About Us - Core Principle
        Menu::create([
            'parent_id' => $about->id,
            'name' => json_encode(['en' => config('dummy.about_us_pages.core_principle.text')]),
            'icon' => 'info',
            'path' => 'admin/about-us/'. config('dummy.about_us_pages.core_principle.url').'/list' . '?page=' . config('dummy.about_us_pages.core_principle.key'),
            'active' => 'admin/about-us/'. config('dummy.about_us_pages.core_principle.url') . '/*',
            'permission' => array('about-us-view'),
        ]);

        // About Us - Structure
        Menu::create([
            'parent_id' => $about->id,
            'name' => json_encode(['en' => config('dummy.about_us_pages.structure.text')]),
            'icon' => 'info',
            'path' => 'admin/about-us/'. config('dummy.about_us_pages.structure.url').'/list' . '?page=' . config('dummy.about_us_pages.structure.key'),
            'active' => 'admin/about-us/'. config('dummy.about_us_pages.structure.url') . '/*',
            'permission' => array('about-us-view'),
        ]);

        // About Us - Office Bearers
        Menu::create([
            'parent_id' => $about->id,
            'name' => json_encode(['en' => config('dummy.about_us_pages.office_bearers.text')]),
            'icon' => 'info',
            'path' => 'admin/about-us/'. config('dummy.about_us_pages.office_bearers.url').'/list' . '?page=' . config('dummy.about_us_pages.office_bearers.key'),
            'active' => 'admin/about-us/'. config('dummy.about_us_pages.office_bearers.url') . '/*',
            'permission' => array('about-us-view'),
        ]);

        // About Us - Secretariat
        Menu::create([
            'parent_id' => $about->id,
            'name' => json_encode(['en' => config('dummy.about_us_pages.secretariat.text')]),
            'icon' => 'info',
            'path' => 'admin/about-us/'. config('dummy.about_us_pages.secretariat.url').'/list' . '?page=' . config('dummy.about_us_pages.secretariat.key'),
            'active' => 'admin/about-us/'. config('dummy.about_us_pages.secretariat.url') . '/*',
            'permission' => array('about-us-view'),
        ]);

        // About Us - Membership
        Menu::create([
            'parent_id' => $about->id,
            'name' => json_encode(['en' => config('dummy.about_us_pages.membership.text')]),
            'icon' => 'info',
            'path' => 'admin/about-us/'. config('dummy.about_us_pages.membership.url').'/list' . '?page=' . config('dummy.about_us_pages.membership.key'),
            'active' => 'admin/about-us/'. config('dummy.about_us_pages.membership.url') . '/*',
            'permission' => array('about-us-view'),
        ]);

        // About Us - Right of Member
        Menu::create([
            'parent_id' => $about->id,
            'name' => json_encode(['en' => config('dummy.about_us_pages.right_of_member.text')]),
            'icon' => 'info',
            'path' => 'admin/about-us/'. config('dummy.about_us_pages.right_of_member.url').'/list' . '?page=' . config('dummy.about_us_pages.right_of_member.key'),
            'active' => 'admin/about-us/'. config('dummy.about_us_pages.right_of_member.url') . '/*',
            'permission' => array('about-us-view'),
        ]);

        // About Us - Responsibility of Member
        Menu::create([
            'parent_id' => $about->id,
            'name' => json_encode(['en' => config('dummy.about_us_pages.responsibility_of_member.text')]),
            'icon' => 'info',
            'path' => 'admin/about-us/'. config('dummy.about_us_pages.responsibility_of_member.url').'/list' . '?page=' . config('dummy.about_us_pages.responsibility_of_member.key'),
            'active' => 'admin/about-us/'. config('dummy.about_us_pages.responsibility_of_member.url') . '/*',
            'permission' => array('about-us-view'),
        ]);

        // User management
        $user = Menu::create([
            'name'          => json_encode(['en' => 'Users']),
            'icon'          => 'users',
            'active'        => 'admin/admin/*',
            'permission'    => array("admin-view"),
        ]);

        Menu::create([
            'parent_id'     => $user->id,
            'name'          => json_encode(['en' => 'Admin']),
            'path'          => 'admin/admin/list',
            'permission'    => array("admin-view"),
            'active'        => 'admin/admin/*',
        ]);

        //Setting management
        $setting = Menu::create([
            'name'          => json_encode(['en' => 'Setting']),
            'icon'          => 'settings',
            'active'        => 'admin/setting/*',
            'permission'    => array('setting-view'),
        ]);

        // Setting - Contact Us
        Menu::create([
            'parent_id'     => $setting->id,
            'name'          => json_encode(['en' => 'Contact Us']),
            'path'          => 'admin/setting/' . 'contact-us',
            'permission'    => array("contact-us-view"),
            'active'        => 'admin/setting/contact-us',
        ]);
    }
}
