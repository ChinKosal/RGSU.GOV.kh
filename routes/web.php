<?php


use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\NewsController;
use App\Http\Controllers\Website\Page10Controller;
use App\Http\Controllers\Website\Page11Controller;
use App\Http\Controllers\Website\Page12Controller;
use App\Http\Controllers\Website\Page13Controller;
use App\Http\Controllers\Website\Page2Controller;
use App\Http\Controllers\Website\Page3Controller;
use App\Http\Controllers\Website\Page4Controller;
use App\Http\Controllers\Website\Page5Controller;
use App\Http\Controllers\Website\Page6Controller;
use App\Http\Controllers\Website\Page7Controller;
use App\Http\Controllers\Website\Page8Controller;
use App\Http\Controllers\Website\Page9Controller;
use App\Http\Controllers\Website\ReadNewController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('coverpage');
Route::get('/page001', [Page2Controller::class, 'page2'])->name('page2');
Route::get('/សារព័ត៌មានប្រចាំថ្ងៃ', [Page3Controller::class, 'page3'])->name('page3');
Route::get('/សកម្មភាពថ្នាក់ដឹកនាំ', [Page4Controller::class, 'page4'])->name('page4');
Route::get('/សន្តិសុខសង្គម', [Page5Controller::class, 'page5'])->name('page5');
Route::get('/PmPage', [Page6Controller::class, 'PM'])->name('page6');
Route::get('/នគរនាលនិងសហគមន៍', [Page7Controller::class, 'page7'])->name('page7');
Route::get('/ប្រយុទ្ធប្រឆាំងនឹងគ្រឿងញៀន', [Page8Controller::class, 'page8'])->name('page8');
Route::get('/សណ្តាប់ធ្នាប់និងចរាចរណ៍', [Page9Controller::class, 'page9'])->name('page9');
Route::get('/វេទិការនិងនគរបាល', [Page10Controller::class, 'page10'])->name('page10');
Route::get('/ទស្សនាវដ្តី', [Page11Controller::class, 'page11'])->name('page11');
Route::get('/វីដេអូ', [Page12Controller::class, 'page12'])->name('page12');
Route::get('/វិទ្យុ', [Page13Controller::class, 'page13'])->name('page13');
Route::get('readspeeches', [ReadNewController::class, 'speeches'])->name('speeches');
// clear cache
Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    return 'Cache is cleared';
});
