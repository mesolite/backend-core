<?php

namespace Mesolite\Database\Seeds;

use Illuminate\Database\Seeder;
use Symfony\Component\Yaml\Yaml;
use Railken\Lem\Attributes;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        app('amethyst')->get('config')->createOrFail([
            'key' => 'app.name',
            'value' => 'Mesolite',
            'visibility' => 'public'
        ]);

        $filename = resource_path("logo.png");

        $result = app('amethyst')->get('file')->createOrFail([
            'name'   => 'app.'.basename($filename),
            'public' => 1,
        ]);

        $resource = $result->getResource();
        $resource
            ->addMedia($filename)
            ->preservingOriginal()
            ->toMediaCollection('app');

        app('amethyst')->get('config')->createOrFail([
            'key' => 'app.logo',
            'value' => $resource->getFullUrl(),
            'visibility' => 'public'
        ]);

        // Allow anyone to see the route file stream
        app('amethyst')->get('permission')->findOrCreateOrFail([
            'type' => 'route',
            'effect' => 'allow',
            'payload' => Yaml::dump([
                'url' => '/api/data/config'
            ]),
        ]);

        // Allow anyone to see files that have public set to 1
        app('amethyst')->get('permission')->findOrCreateOrFail([
            'type' => 'data',
            'effect' => 'allow',
            'payload' => Yaml::dump([
                'data' => 'config',
                'action' => 'query',
                'filter' => 'visibility eq "public"'
            ]),
        ]);
    }
}
