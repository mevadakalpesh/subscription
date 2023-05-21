<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

//interface 
use App\Http\RepoInterface\PostInterfaceRepo;
use App\Http\RepoInterface\StripeInterfaceRepo;

//repository class
use App\Http\RepoClass\PostRepo;
use App\Http\RepoClass\StripeRepo;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
       $this->app->bind(PostInterfaceRepo::class,PostRepo::class);
       $this->app->bind(StripeInterfaceRepo::class,StripeRepo::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
