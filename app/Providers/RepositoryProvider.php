<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\RepoInterface\ProductRepoInterface;
use App\RepoClass\ProductRepoClass;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepoInterface::class,ProductRepoClass::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
