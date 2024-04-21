<?php

namespace Farzad\Subscription;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;

class SubscriptionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind("Subscription");
        $this->mergeConfigFrom(__DIR__."/Config/app.php","Subscription");
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__."/Config/app.php" => config_path("app.php"),
            __DIR__ . '/database/migrations/create_plan.php' => $this->app->databasePath()."/migrations/create_plan.php",
            __DIR__ . '/database/migrations/create_plan_items.php' => $this->app->databasePath()."/migrations/create_plan_items.php",
            __DIR__ . '/database/migrations/create_plan_has_items.php' => $this->app->databasePath()."/migrations/create_plan_has_items.php",
            __DIR__ . '/database/migrations/create_plan_subscription_items.php' => $this->app->databasePath()."/migrations/create_plan_subscription_items.php",
            __DIR__ . '/database/migrations/create_plan_subscriptions.php' => $this->app->databasePath()."/migrations/create_plan_subscriptions.php",
        ]);
    }
}
