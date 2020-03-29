<?php

namespace Mesolite;

use App\Compass\RouteResolver;
use Davidhsianturi\Compass\Contracts\RouteResolverContract;
use Illuminate\Support\ServiceProvider;
use Amethyst\Models\DataSchema;
use Amethyst\Models\RelationSchema;
use Amethyst\Models\AttributeSchema;
use Amethyst\Models\DataView;
use Mesolite\Observers\DataSchemaObserver;
use Mesolite\Observers\RelationSchemaObserver;
use Mesolite\Observers\AttributeSchemaObserver;
use Mesolite\Observers\DataViewObserver;

class MesoliteServiceProvider extends ServiceProvider
{
    /**
     * @inherit
     */
    public function register()
    {
        $this->app->register(\Amethyst\Providers\AccountServiceProvider::class);
        $this->app->register(\Amethyst\Providers\ActionServiceProvider::class);
        $this->app->register(\Amethyst\Providers\AttributeSchemaServiceProvider::class);
        $this->app->register(\Amethyst\Providers\AuthenticationServiceProvider::class);
        $this->app->register(\Amethyst\Providers\DataSchemaServiceProvider::class);
        $this->app->register(\Amethyst\Providers\DataViewServiceProvider::class);
        $this->app->register(\Amethyst\Providers\GroupServiceProvider::class);
        $this->app->register(\Amethyst\Providers\OwnerServiceProvider::class);
        $this->app->register(\Amethyst\Providers\PermissionServiceProvider::class);
        $this->app->register(\Amethyst\Providers\RelationSchemaServiceProvider::class);
        $this->app->register(\Amethyst\Providers\SettingServiceProvider::class);
        $this->app->register(\Amethyst\Providers\UserServiceProvider::class);


        $this->commands([\Mesolite\Console\Commands\Install::class]);
    }

    /**
     * @inherit
     */
    public function boot()
    {
        app('amethyst.data-schema')->boot();
        app('amethyst.attributable')->boot();
        app('amethyst.relation-schema')->boot();
        app('eloquent.mapper')->boot();

        DataSchema::observe(DataSchemaObserver::class);
        RelationSchema::observe(RelationSchemaObserver::class);
        AttributeSchema::observe(AttributeSchemaObserver::class);
        DataView::observe(DataViewObserver::class);
    }
}
