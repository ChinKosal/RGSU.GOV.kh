<?php

namespace Database\Seeders;

use App\Models\ModulePermission;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        ModulePermission::truncate();
        Permission::truncate();
        Schema::enableForeignKeyConstraints();
        $view                   = "View";
        $create                 = "Create";
        $edit                   = "Edit";
        $delete                 = "Delete";
        $stCategory             = 1;
        $stSlider               = 2;
        $stCommittee            = 3;
        $stDocument             = 4;
        $stGfatmGrant           = 5;
        $stPrincipalRecipient   = 6;
        $stNews                 = 7;
        $stActivity             = 8;
        $stGallery              = 9;
        $stVideo                = 10;
        $stCareer               = 11;
        $stPartner              = 12;
        $stAboutUs              = 13;
        $stSocialMedia          = 12;
        $stUser                 = 100;
        $stSetting              = 101;

        // Category
        $category = ModulePermission::create([
            'name' => 'category',
            'sort_no' => $stCategory,
        ]);
        Permission::insert([
            [
                'display_name' => $view,
                'name' => 'category-view',
                'guard_name' => 'admin',
                'module_id' => $category->id,
            ],
            [
                'display_name' => $create,
                'name' => 'category-create',
                'guard_name' => 'admin',
                'module_id' => $category->id,
            ],
            [
                'display_name' => $edit,
                'name' => 'category-update',
                'guard_name' => 'admin',
                'module_id' => $category->id,
            ],
            [
                'display_name' => $delete,
                'name' => 'category-delete',
                'guard_name' => 'admin',
                'module_id' => $category->id,
            ]
        ]);

        // Slider
        $slider = ModulePermission::create([
            'name' => 'slider',
            'sort_no' => $stSlider,
        ]);
        Permission::insert([
            [
                'display_name' => $view,
                'name' => 'slider-view',
                'guard_name' => 'admin',
                'module_id' => $slider->id,
            ],
            [
                'display_name' => $create,
                'name' => 'slider-create',
                'guard_name' => 'admin',
                'module_id' => $slider->id,
            ],
            [
                'display_name' => $edit,
                'name' => 'slider-update',
                'guard_name' => 'admin',
                'module_id' => $slider->id,
            ],
            [
                'display_name' => $delete,
                'name' => 'slider-delete',
                'guard_name' => 'admin',
                'module_id' => $slider->id,
            ]
        ]);

        // Committee
        $committee = ModulePermission::create([
            'name'      => 'committee',
            'sort_no'   => $stCommittee,
        ]);
        Permission::insert([
            [
                'display_name' => $view,
                'name' => 'committee-view',
                'guard_name' => 'admin',
                'module_id' => $committee->id,
            ],
            [
                'display_name' => $create,
                'name' => 'committee-create',
                'guard_name' => 'admin',
                'module_id' => $committee->id,
            ],
            [
                'display_name' => $edit,
                'name' => 'committee-update',
                'guard_name' => 'admin',
                'module_id' => $committee->id,
            ],
            [
                'display_name' => $delete,
                'name' => 'committee-delete',
                'guard_name' => 'admin',
                'module_id' => $committee->id,
            ]
        ]);

        // Document
        $document = ModulePermission::create([
            'name' => 'document',
            'sort_no' => $stDocument,
        ]);
        Permission::insert([
            [
                'display_name' => $view,
                'name' => 'document-view',
                'guard_name' => 'admin',
                'module_id' => $document->id,
            ],
            [
                'display_name' => $create,
                'name' => 'document-create',
                'guard_name' => 'admin',
                'module_id' => $document->id,
            ],
            [
                'display_name' => $edit,
                'name' => 'document-update',
                'guard_name' => 'admin',
                'module_id' => $document->id,
            ],
            [
                'display_name' => $delete,
                'name' => 'document-delete',
                'guard_name' => 'admin',
                'module_id' => $document->id,
            ]
        ]);

        // GFATM Grant
        $gfatmGrant = ModulePermission::create([
            'name' => 'gfatm-grant',
            'sort_no' => $stGfatmGrant,
        ]);
        Permission::insert([
            [
                'display_name' => $view,
                'name' => 'gfatm-grant-view',
                'guard_name' => 'admin',
                'module_id' => $gfatmGrant->id,
            ],
            [
                'display_name' => $create,
                'name' => 'gfatm-grant-create',
                'guard_name' => 'admin',
                'module_id' => $gfatmGrant->id,
            ],
            [
                'display_name' => $edit,
                'name' => 'gfatm-grant-update',
                'guard_name' => 'admin',
                'module_id' => $gfatmGrant->id,
            ],
            [
                'display_name' => $delete,
                'name' => 'gfatm-grant-delete',
                'guard_name' => 'admin',
                'module_id' => $gfatmGrant->id,
            ]
        ]);

        // Principal Recipient
        $principalRecipient = ModulePermission::create([
            'name' => 'principal-recipient',
            'sort_no' => $stPrincipalRecipient,
        ]);
        Permission::insert([
            [
                'display_name' => $view,
                'name' => 'principal-recipient-view',
                'guard_name' => 'admin',
                'module_id' => $principalRecipient->id,
            ],
            [
                'display_name' => $create,
                'name' => 'principal-recipient-create',
                'guard_name' => 'admin',
                'module_id' => $principalRecipient->id,
            ],
            [
                'display_name' => $edit,
                'name' => 'principal-recipient-update',
                'guard_name' => 'admin',
                'module_id' => $principalRecipient->id,
            ],
            [
                'display_name' => $delete,
                'name' => 'principal-recipient-delete',
                'guard_name' => 'admin',
                'module_id' => $principalRecipient->id,
            ]
        ]);

        // News
        $news = ModulePermission::create([
            'name' => 'news',
            'sort_no' => $stNews,
        ]);
        Permission::insert([
            [
                'display_name' => $view,
                'name' => 'news-view',
                'guard_name' => 'admin',
                'module_id' => $news->id,
            ],
            [
                'display_name' => $create,
                'name' => 'news-create',
                'guard_name' => 'admin',
                'module_id' => $news->id,
            ],
            [
                'display_name' => $edit,
                'name' => 'news-update',
                'guard_name' => 'admin',
                'module_id' => $news->id,
            ],
            [
                'display_name' => $delete,
                'name' => 'news-delete',
                'guard_name' => 'admin',
                'module_id' => $news->id,
            ]
        ]);

        // Activity
        $activity = ModulePermission::create([
            'name' => 'activity',
            'sort_no' => $stActivity,
        ]);
        Permission::insert([
            [
                'display_name' => $view,
                'name' => 'activity-view',
                'guard_name' => 'admin',
                'module_id' => $activity->id,
            ],
            [
                'display_name' => $create,
                'name' => 'activity-create',
                'guard_name' => 'admin',
                'module_id' => $activity->id,
            ],
            [
                'display_name' => $edit,
                'name' => 'activity-update',
                'guard_name' => 'admin',
                'module_id' => $activity->id,
            ],
            [
                'display_name' => $delete,
                'name' => 'activity-delete',
                'guard_name' => 'admin',
                'module_id' => $activity->id,
            ]
        ]);

        // Gallery
        $gallery = ModulePermission::create([
            'name' => 'gallery',
            'sort_no' => $stGallery,
        ]);
        Permission::insert([
            [
                'display_name' => $view,
                'name' => 'gallery-view',
                'guard_name' => 'admin',
                'module_id' => $gallery->id,
            ],
            [
                'display_name' => $create,
                'name' => 'gallery-create',
                'guard_name' => 'admin',
                'module_id' => $gallery->id,
            ],
        ]);

        // video
        $video = ModulePermission::create([
            'name' => 'video',
            'sort_no' => $stVideo,
        ]);
        Permission::insert([
            [
                'display_name' => $view,
                'name' => 'video-view',
                'guard_name' => 'admin',
                'module_id' => $video->id,
            ],
            [
                'display_name' => $create,
                'name' => 'video-create',
                'guard_name' => 'admin',
                'module_id' => $video->id,
            ],
        ]);

        // Career
        $career = ModulePermission::create([
            'name' => 'career',
            'sort_no' => $stCareer,
        ]);
        Permission::insert([
            [
                'display_name' => $view,
                'name' => 'career-view',
                'guard_name' => 'admin',
                'module_id' => $career->id,
            ],
            [
                'display_name' => $create,
                'name' => 'career-create',
                'guard_name' => 'admin',
                'module_id' => $career->id,
            ],
            [
                'display_name' => $edit,
                'name' => 'career-update',
                'guard_name' => 'admin',
                'module_id' => $career->id,
            ],
            [
                'display_name' => $delete,
                'name' => 'career-delete',
                'guard_name' => 'admin',
                'module_id' => $career->id,
            ]
        ]);

        // Partner
        $partner = ModulePermission::create([
            'name' => 'partner',
            'sort_no' => $stPartner,
        ]);
        Permission::insert([
            [
                'display_name' => $view,
                'name' => 'partner-view',
                'guard_name' => 'admin',
                'module_id' => $partner->id,
            ],
            [
                'display_name' => $create,
                'name' => 'partner-create',
                'guard_name' => 'admin',
                'module_id' => $partner->id,
            ],
            [
                'display_name' => $edit,
                'name' => 'partner-update',
                'guard_name' => 'admin',
                'module_id' => $partner->id,
            ],
            [
                'display_name' => $delete,
                'name' => 'partner-delete',
                'guard_name' => 'admin',
                'module_id' => $partner->id,
            ]
        ]);

        // About Us
        $aboutUs = ModulePermission::create([
            'name' => 'about-us',
            'sort_no' => $stAboutUs,
        ]);
        Permission::insert([
            [
                'display_name' => $view,
                'name' => 'about-us-view',
                'guard_name' => 'admin',
                'module_id' => $aboutUs->id,
            ],
            [
                'display_name' => $create,
                'name' => 'about-us-create',
                'guard_name' => 'admin',
                'module_id' => $aboutUs->id,
            ],
            [
                'display_name' => $edit,
                'name' => 'about-us-update',
                'guard_name' => 'admin',
                'module_id' => $aboutUs->id,
            ],
            [
                'display_name' => $delete,
                'name' => 'about-us-delete',
                'guard_name' => 'admin',
                'module_id' => $aboutUs->id,
            ]
        ]);

        //admin
        $admin = ModulePermission::create([
            'name' => 'admin',
            'sort_no' => $stUser,
        ]);
        Permission::insert([
            [
                'display_name' => $view,
                'name' => 'admin-view',
                'guard_name' => 'admin',
                'module_id' => $admin->id,
            ],
            [
                'display_name' => $create,
                'name' => 'admin-create',
                'guard_name' => 'admin',
                'module_id' => $admin->id,
            ],
            [
                'display_name' => $edit,
                'name' => 'admin-update',
                'guard_name' => 'admin',
                'module_id' => $admin->id,
            ],
            [
                'display_name' => $delete,
                'name' => 'admin-delete',
                'guard_name' => 'admin',
                'module_id' => $admin->id,
            ]
        ]);

        // Social Media
        $socialMedia = ModulePermission::create([
            'name' => 'social-media',
            'sort_no' => $stSocialMedia,
        ]);
        Permission::insert([
            [
                'display_name' => $view,
                'name' => 'social-media-view',
                'guard_name' => 'admin',
                'module_id' => $socialMedia->id,
            ],
            [
                'display_name' => $create,
                'name' => 'social-media-create',
                'guard_name' => 'admin',
                'module_id' => $socialMedia->id,
            ],
            [
                'display_name' => $edit,
                'name' => 'social-media-update',
                'guard_name' => 'admin',
                'module_id' => $socialMedia->id,
            ],
        ]);

        // Setting
        $setting = ModulePermission::create([
            'name' => 'setting',
            'sort_no' => $stSetting,
        ]);
        Permission::insert([
            [
                'display_name' => $view,
                'name' => 'setting-view',
                'guard_name' => 'admin',
                'module_id' => $setting->id,
            ],
            [
                'display_name' => $edit,
                'name' => 'setting-create',
                'guard_name' => 'admin',
                'module_id' => $setting->id,
            ]
        ]);
    }
}
