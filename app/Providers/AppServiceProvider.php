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
        $this->app->bind('path.public', function(){
             return base_path().'/public_html';
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
        $name = Site::where('variable', 'sitename')->first();
        if($name == null){
            $sitename = "Tournament";
        }else{
            $sitename = $name->value;
        }
	$avatar = Avatar::create($sitename.' ')->setShape('square')->setBackground('#000000')->setDimension(100)->toBase64();
        View::share(['favicon'=>$avatar, 'sitename'=>ucwords($sitename)]);
        Schema::defaultStringLength(191);
    }
}
