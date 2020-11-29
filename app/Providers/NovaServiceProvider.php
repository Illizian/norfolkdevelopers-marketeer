<?php

namespace App\Providers;

use \App\Policies\UserPolicy;
use \Illizian\NovaResourceCalendar\NovaResourceCalendar;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use \Vyuldashev\NovaPermission\NovaPermissionTool;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Nova::style("nova-theme", public_path("css/nova.css"));
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return $user->can('tool.nova');
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new Help,
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            (new NovaResourceCalendar)
                ->canSee(function($request) {
                    return $request->user()->can('model.event.read');
                }),
            (new NovaPermissionTool)
                ->rolePolicy(UserPolicy::class)
                ->permissionPolicy(UserPolicy::class),
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
