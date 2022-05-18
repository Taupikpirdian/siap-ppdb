<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\User;
use App\File;
use App\Document;
use View;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        setlocale(LC_ALL, 'id_ID.utf8');
        Carbon::setLocale('id_ID.utf8');

        
        // $count_users = User::count();
        // $count_documents = Document::count();

        // View::share ( 'count_users', $count_users );
        // View::share ( 'count_documents', $count_documents );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
    }
}
