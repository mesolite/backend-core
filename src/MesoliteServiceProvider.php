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
use Mesolite\Observers\OwnableWithDataSchemaObserver;

class MesoliteServiceProvider extends ServiceProvider
{
    /**
     * @inherit
     */
    public function register()
    {
        $this->app->register(\Amethyst\Providers\ActionServiceProvider::class);
        $this->app->register(\Amethyst\Providers\AttributeSchemaServiceProvider::class);
        $this->app->register(\Amethyst\Providers\AuthenticationServiceProvider::class);
        $this->app->register(\Amethyst\Providers\ConfigServiceProvider::class);
        $this->app->register(\Amethyst\Providers\DataSchemaServiceProvider::class);
        $this->app->register(\Amethyst\Providers\DataViewServiceProvider::class);
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
        app('amethyst.attribute-schema')->boot();
        app('amethyst.relation-schema')->boot();
        app('eloquent.mapper')->boot();
        
        AttributeSchema::observe(AttributeSchemaObserver::class);
        DataSchema::observe(DataSchemaObserver::class);
        DataSchema::observe(OwnableWithDataSchemaObserver::class);
        RelationSchema::observe(RelationSchemaObserver::class);
        DataView::observe(DataViewObserver::class);

    }
}
