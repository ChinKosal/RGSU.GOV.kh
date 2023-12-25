<?php

use App\Http\Controllers\Admin as Admin;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdvertisementController;
use App\Http\Controllers\Admin\CareerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommitteeController;
use App\Http\Controllers\Admin\LibraryController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\DatabaseSystemController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\GfatmGrantController;
use App\Http\Controllers\Admin\GreetingController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\PrincipalController;
use App\Http\Controllers\Admin\QuickLinkController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SocialMediaController;
use App\Http\Controllers\Admin\VideoController;
use App\Services\FileManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('clear-cache', function () {
    Artisan::call('optimize:clear');
    return "Cache is cleared";
});

Route::get("/change-locale/{locale}", [Admin\ChangeLocaleController::class, 'changeLocale'])->name('change-locale');
Route::middleware(['locale'])->group(function () {
    // Auth
    Route::prefix('auth')->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin-login');
        });
        Route::get('/login', [Admin\AdminController::class, 'login'])->name('login');
        Route::post('/login/post', [Admin\AuthController::class, 'login'])->name('login-post');
        Route::get('/sign-out', [Admin\AuthController::class, 'signOut'])->name('sign-out');
    });

    Route::middleware(['AdminGuard'])->group(function () {
        Route::get('seeder', function () {
            Artisan::call('db:seed');
            return "DB Seed success";
        });
        Route::get('migrate', function () {
            Artisan::call('migrate');
            return 'Migrate success';
        });
        Route::get('optimize', function () {
            Artisan::call('optimize:clear');
            return 'Optimize success';
        });

        Route::get('/', function () {
            return redirect()->route('admin-dashboard');
        });
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Admin
        Route::prefix('admin')
            ->controller(AdminController::class)
            ->name('admin-')
            ->group(function () {
                Route::get('list',  'index')->name('list');
                Route::get('data',  'data')->name('data');
                Route::post('save',  'onSave')->name('save');
                Route::post('update',  'onUpdate')->name('update');
                Route::post('status',  'onUpdateStatus')->name('status');
                Route::post('save-password',  'onSavePassword')->name('save-password');
                Route::delete('delete',  'onDelete')->name('delete');
                Route::put('restore',  'onRestore')->name('restore');

                // userPermission
                Route::get('user-permission',  'userPermission')->name('user-permission');
                Route::post('user-permission-save',  'userPermissionSave')->name('user-permission-save');
        });

        //File Manager
        Route::prefix('file-manager')->name('file-manager-')->group(function () {
            Route::get('/index', [FileManager::class, 'index'])->name('index');
            Route::get('/first', [FileManager::class, 'first'])->name('first');
            Route::get('/files', [FileManager::class, 'getFiles'])->name('files');
            Route::get('/folders', [FileManager::class, 'getFolders'])->name('folders');
            Route::post('/upload', [FileManager::class, 'uploadFile'])->name('upload');
            Route::post('/rename-file', [FileManager::class, 'renameFile'])->name('rename-file');
            Route::delete('/delete-file', [FileManager::class, 'deleteFile'])->name('delete-file');

            //folder
            Route::post('/create-folder', [FileManager::class, 'createFolder'])->name('create-folder');
            Route::post('/rename-folder', [FileManager::class, 'renameFolder'])->name('rename-folder');
            Route::delete('/delete-folder', [FileManager::class, 'deleteFolder'])->name('delete-folder');

            //trash bin
            Route::delete('/delete-all', [FileManager::class, 'deleteAll'])->name('delete-all');
            Route::put('/restore-all', [FileManager::class, 'restoreAll'])->name('restore-all');
        });

        // Category
        Route::prefix('category')
            ->controller(CategoryController::class)
            ->name('category-')
            ->group(function () {
                Route::get('{type}/list', 'index')->name('list');
                Route::get('data', 'data')->name('data');
                Route::post('store', 'onStore')->name('store');
                Route::delete('delete', 'onDelete')->name('delete');
                Route::put('restore', 'onRestore')->name('restore');
                Route::post('status', 'onUpdateStatus')->name('status');

                // select option
                Route::get('option', 'onOption')->name('option');

                // ordering
                Route::get('ordering', 'onOrdering')->name('ordering');
        });

        // Slider
        Route::prefix('slider')
            ->controller(SliderController::class)
            ->name('slider-')
            ->group(function () {
                Route::get('list', 'index')->name('list');
                Route::get('data', 'data')->name('data');
                Route::get('/ordering', 'getOrdering')->name('ordering');
                Route::post('store', 'onStore')->name('store');
                Route::post('update', 'onUpdate')->name('update');
                Route::post('status', 'onUpdateStatus')->name('status');
        });

        // Committee
        Route::prefix('committee')
            ->controller(CommitteeController::class)
            ->name('committee-')
            ->group(function () {
                Route::get('{type?}/list', 'index')->name('list');
                Route::get('data', 'data')->name('data');
                Route::get('page', 'page')->name('page');
                Route::post('store', 'onStore')->name('store');
                Route::delete('bulk-hide-show', 'bulkHideShow')->name('bulk-hide-show');
                Route::post('save-single-option', 'saveSingleOption')->name('saveSingleOption');
                Route::delete('delete', 'onDelete')->name('delete');
                Route::put('restore', 'onRestore')->name('restore');
                Route::delete('destroy', 'onDestroy')->name('destroy');
        });

        // Document
        Route::prefix('document')
            ->controller(DocumentController::class)
            ->name('document-')
            ->group(function () {
                Route::get('{type?}/list', 'index')->name('list');
                Route::get('data', 'data')->name('data');
                Route::post('store', 'onStore')->name('store');
                Route::delete('bulk-hide-show', 'bulkHideShow')->name('bulk-hide-show');
                Route::post('save-single-option', 'saveSingleOption')->name('saveSingleOption');
                Route::delete('delete', 'onDelete')->name('delete');
                Route::put('restore', 'onRestore')->name('restore');
                Route::delete('destroy', 'onDestroy')->name('destroy');
        });

        // Gfatm Grant
        Route::prefix('gfatm-grant')
            ->controller(GfatmGrantController::class)
            ->name('gfatm-grant-')
            ->group(function () {
                Route::get('list', 'index')->name('list');
                Route::get('data', 'data')->name('data');
                Route::post('store', 'onStore')->name('store');
                Route::delete('bulk-hide-show', 'bulkHideShow')->name('bulk-hide-show');
                Route::post('save-single-option', 'saveSingleOption')->name('saveSingleOption');
                Route::delete('delete', 'onDelete')->name('delete');
                Route::put('restore', 'onRestore')->name('restore');
                Route::delete('destroy', 'onDestroy')->name('destroy');
        });

        // Principal Recipient
        Route::prefix('principal-recipient')
            ->controller(PrincipalController::class)
            ->name('principal-recipient-')
            ->group(function () {
                Route::get('{type?}/list', 'index')->name('list');
                Route::get('data', 'data')->name('data');
                Route::get('page', 'page')->name('page');
                Route::post('store', 'onStore')->name('store');
                Route::delete('bulk-hide-show', 'bulkHideShow')->name('bulk-hide-show');
                Route::post('save-single-option', 'saveSingleOption')->name('saveSingleOption');
                Route::delete('delete', 'onDelete')->name('delete');
                Route::put('restore', 'onRestore')->name('restore');
                Route::delete('destroy', 'onDestroy')->name('destroy');
        });

        // News
        Route::controller(NewsController::class)
            ->prefix('news')
            ->name('news-')
            ->group(function () {
                Route::get('list', 'index')->name('list');
                Route::get('data', 'data')->name('data');
                Route::post('store', 'onStore')->name('store');
                Route::delete('bulk-hide-show', 'bulkHideShow')->name('bulk-hide-show');
                Route::post('save-single-option', 'saveSingleOption')->name('saveSingleOption');
                Route::delete('delete', 'onDelete')->name('delete');
                Route::put('restore', 'onRestore')->name('restore');
                Route::delete('destroy', 'onDestroy')->name('destroy');

                // News Category
                Route::get('category/option', 'category')->name('category');
        });

        // Activity
        Route::controller(ActivityController::class)
            ->prefix('activity')
            ->name('activity-')
            ->group(function () {
                Route::get('list', 'index')->name('list');
                Route::get('data', 'data')->name('data');
                Route::post('store', 'onStore')->name('store');
                Route::delete('bulk-hide-show', 'bulkHideShow')->name('bulk-hide-show');
                Route::post('save-single-option', 'saveSingleOption')->name('saveSingleOption');
                Route::delete('delete', 'onDelete')->name('delete');
                Route::put('restore', 'onRestore')->name('restore');
                Route::delete('destroy', 'onDestroy')->name('destroy');
        });

        // Gallery
        Route::controller(GalleryController::class)
            ->prefix('gallery')
            ->name('gallery-')
            ->group(function () {
                Route::get('list', 'index')->name('list');
                Route::get('data', 'data')->name('data');
                Route::post('store', 'onStore')->name('store');
                Route::delete('bulk-hide-show', 'bulkHideShow')->name('bulk-hide-show');
                Route::post('save-single-option', 'saveSingleOption')->name('saveSingleOption');
                Route::delete('delete', 'onDelete')->name('delete');
                Route::put('restore', 'onRestore')->name('restore');
                Route::delete('destroy', 'onDestroy')->name('destroy');
        });

        // Video
        Route::controller(VideoController::class)
            ->prefix('video')
            ->name('video-')
            ->group(function () {
                Route::get('list', 'index')->name('list');
                Route::get('data', 'data')->name('data');
                Route::post('save', 'onSave')->name('save');
                Route::post('save-single-option', 'saveSingleOption')->name('saveSingleOption');
        });

        // Career
        Route::controller(CareerController::class)
            ->prefix('career')
            ->name('career-')
            ->group(function () {
                Route::get('list', 'index')->name('list');
                Route::get('data', 'data')->name('data');
                Route::post('store', 'onStore')->name('store');
                Route::delete('bulk-hide-show', 'bulkHideShow')->name('bulk-hide-show');
                Route::post('save-single-option', 'saveSingleOption')->name('saveSingleOption');
                Route::delete('delete', 'onDelete')->name('delete');
                Route::put('restore', 'onRestore')->name('restore');
                Route::delete('destroy', 'onDestroy')->name('destroy');
        });

        // Partner
        Route::controller(PartnerController::class)
            ->prefix('partner')
            ->name('partner-')
            ->group(function () {
                Route::get('list', 'index')->name('list');
                Route::get('data', 'data')->name('data');
                Route::post('save', 'onSave')->name('save');
                Route::post('save-single-option', 'saveSingleOption')->name('saveSingleOption');
        });

        // About Us
        Route::prefix('about-us')
            ->controller(PageController::class)
            ->name('about-us-')
            ->group(function () {
                Route::get('{page}/list', 'onAboutUs')->name('list');
                Route::get('page/data', 'data')->name('data');
                Route::post('store', 'onStore')->name('store');

                // page category
                Route::get('category/option', 'category')->name('category');

                // row data
                Route::get('row-data/{page}', 'getRowData')->name('row-data');
                Route::post('row-data/save/{page}', 'onRowDataSave')->name('row-data-save');
                Route::delete('row-data/delete', 'onDelete')->name('delete');
                Route::put('row-data/restore', 'onRestore')->name('restore');
                Route::post('row-data/status', 'onUpdateStatus')->name('status');
                Route::put('restore', 'onRestore')->name('restore');
                Route::delete('destroy', 'onDestroy')->name('destroy');
        });

        // Social Media
        Route::controller(SocialMediaController::class)
            ->prefix('social-media')
            ->name('social-media-')
            ->group(function () {
                Route::get('{type}/list', 'index')->name('list');
                Route::get('data', 'data')->name('data');
                Route::post('save', 'onSave')->name('save');
                Route::post('save-single-option', 'saveSingleOption')->name('saveSingleOption');
        });

        /// Setting
        Route::prefix('setting')->name('setting-')->group(function () {
            Route::get('/{page}', [Admin\PageController::class, 'onPage'])->name('page');
            Route::post('save-page', [Admin\PageController::class, 'onSave'])->name('page-update');
            Route::get('/page-data/{type}', [Admin\PageController::class, 'getData'])->name('page-data');
            Route::post('save-contact', [Admin\PageController::class, 'onSaveContact'])->name('save-contact');
        });

    });
});
