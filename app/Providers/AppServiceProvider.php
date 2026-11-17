<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
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
        Paginator::useBootstrap();

        view()->composer('admin.*', function ($view) {
            try {
                $pendingOrders = \Illuminate\Support\Facades\DB::table('orderproduct')->where('Status', '1')->count();
                $unreadContacts = \Illuminate\Support\Facades\DB::table('contact')->where('Status', '0')->count();
                $view->with([
                    'pendingOrdersCount' => $pendingOrders,
                    'unreadContactsCount' => $unreadContacts,
                ]);
            } catch (\Exception $e) {
                // Ignore if DB not ready
            }
        });
    }
}
