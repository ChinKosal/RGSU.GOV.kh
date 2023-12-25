<?php

namespace App\Providers;

use App\Macros\BuilderMacro;
use App\Models\Menu;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Builder::macro("whereLike", function ($field, $value) {
            return with(new BuilderMacro($this))->whereLike($field, $value);
        });
        Builder::macro("iud", function ($values, $uniquesBy, $fields) {
            return with(new BuilderMacro($this))->insertUpdateDelete($values, $uniquesBy, $fields);
        });

        $this->loadViewsFrom(resource_path('admin/views'), 'admin');
        $this->loadViewsFrom(resource_path('website/views'), 'website');
        Schema::defaultStringLength(125);

        if (Schema::hasTable('menus') && Request::is('admin/*')) {
            $menu = Menu::with('children')->whereNull('disabled_at')->whereNull('parent_id')->orderBy('ordering')->get();
            view()->share('menu', $menu);
        }
    }
}
