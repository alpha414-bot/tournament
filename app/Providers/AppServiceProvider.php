<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Laravolt\Avatar\Facade as Avatar;
use Illuminate\Support\Facades\View;
use App\Models\Site;


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
        $this->app->bind('path.public', function () {
            return base_path() . '/public_html';
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $name = "Tournament";
        if (Schema::hasTable('migrations')) {
            // migration has been ran
            $name = Site::where('variable', 'sitename')->first();
            $name = !$name->value ? 'Tournament' : $name->value;
        }

        $avatar = Avatar::create($name . ' ')->setShape('square')->setBackground('#000000')->setDimension(100)->toBase64();
        View::share(['favicon' => $avatar, 'sitename' => ucwords($name)]);
        Schema::defaultStringLength(191);
    }
}
