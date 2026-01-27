<?php

declare(strict_types=1);

namespace TentaPress\AdminShell;

use Illuminate\Support\ServiceProvider;
use TentaPress\AdminShell\Navigation\MenuRepository;
use TentaPress\System\Plugin\PluginRegistry;

final class AdminShellServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MenuRepository::class, fn (): MenuRepository => new MenuRepository(
            registry: $this->app->make(PluginRegistry::class),
        ));
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'tentapress-admin');

        // Routes are grouped inside routes/admin.php using AdminRoutes::group(...)
        $this->loadRoutesFrom(__DIR__.'/../routes/admin.php');

        // Inject menu + common admin view data into the shell layout.
        view()->composer('tentapress-admin::layouts.shell', function ($view): void {
            $menus = $this->app->make(MenuRepository::class);

            $view->with('tpMenu', $menus->all());
        });

        view()->composer('tentapress-admin::partials.sidebar', function ($view): void {
            $menus = $this->app->make(MenuRepository::class);

            $view->with('tpMenu', $menus->all());
        });
    }
}
