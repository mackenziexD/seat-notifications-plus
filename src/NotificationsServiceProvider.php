<?php

namespace Helious\SeatNotificationsPlus;

use Seat\Services\AbstractSeatPlugin;
use Illuminate\Console\Scheduling\Schedule;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Helious\SeatNotificationsPlus\Observers\CharacterNotificationObserver;
use Helious\SeatNotificationsPlus\Console\TestObs;
use Helious\SeatNotificationsPlus\Console\ImportNotificationsTest;

class NotificationsServiceProvider extends AbstractSeatPlugin
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/seat-notifications-plus.php', 'seat-notifications');
        $this->mergeConfigFrom(__DIR__ . '/Config/seat-notifications-plus.sidebar.php', 'package.sidebar');

        $this->mergeConfigFrom(
            __DIR__ . '/Config/seat-notifications-plus.alerts.php', 'notifications.alerts'
        );

        $this->registerPermissions(__DIR__ . '/Config/seat-notifications.permissions.php', 'seat-notifications');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

        $this->addCommands();

        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'seat-beacons');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        
        CharacterNotification::observe(CharacterNotificationObserver::class);

    }

    
    private function addCommands() 
    {
        $this->commands([
            TestObs::class,
            ImportNotificationsTest::class,
        ]);
    }


    /**
     * Get the package's routes.
     *
     * @return string
     */
    protected function getRouteFile()
    {
        return __DIR__.'/routes.php';
    }


    /**
     * Return the plugin public name as it should be displayed into settings.
     *
     * @return string
     * @example SeAT Web
     *
     */
    public function getName(): string
    {
        return 'SeAT Notifications+';
    }

    /**
     * Return the plugin repository address.
     *
     * @example https://github.com/eveseat/web
     *
     * @return string
     */
    public function getPackageRepositoryUrl(): string
    {
        return 'https://github.com/mackenziexD/seat-notifications';
    }

    /**
     * Return the plugin technical name as published on package manager.
     *
     * @return string
     * @example web
     *
     */
    public function getPackagistPackageName(): string
    {
        return 'seat-notifications-plus';
    }

    /**
     * Return the plugin vendor tag as published on package manager.
     *
     * @return string
     * @example eveseat
     *
     */
    public function getPackagistVendorName(): string
    {
        return 'helious';
    }
}