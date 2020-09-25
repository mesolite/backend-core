<?php

namespace Mesolite\Database\Seeds;

use Illuminate\Database\Seeder;
use Symfony\Component\Yaml\Yaml;
use Railken\Lem\Attributes;

class PermissionConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        app('amethyst')->get('relation-schema')->createOrFail([
            'name'   => 'policies',
            'type'   => 'MorphToMany',
            'data' => 'config',
            'payload' => Yaml::dump([
                'target' => 'policy',
                'key' => 'config-policy'
            ])
        ]);

        app('amethyst')->get('permission')->findOrCreateOrFail([
            'type' => 'route',
            'effect' => 'allow',
            'payload' => Yaml::dump([
                'url' => '/api/data/config'
            ]),
        ]);

        app('amethyst')->get('permission')->findOrCreateOrFail([
            'type' => 'data',
            'effect' => 'allow',
            'payload' => Yaml::dump([
                'data' => 'config',
                'action' => 'query',
                'filter' => 'policies.name eq "public"'
            ]),
        ]);
    }
}
