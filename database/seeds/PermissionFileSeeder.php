<?php

namespace Mesolite\Database\Seeds;

use Illuminate\Database\Seeder;
use Symfony\Component\Yaml\Yaml;
use Railken\Lem\Attributes;

class PermissionFileSeeder extends Seeder
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
            'data' => 'file',
            'payload' => Yaml::dump([
                'target' => 'policy',
                'key' => 'file-policy'
            ])
        ]);

        app('amethyst')->get('permission')->findOrCreateOrFail([
            'type' => 'route',
            'effect' => 'allow',
            'payload' => Yaml::dump([
                'url' => '/api/data/file'
            ]),
        ]);

        app('amethyst')->get('permission')->findOrCreateOrFail([
            'type' => 'data',
            'effect' => 'allow',
            'payload' => Yaml::dump([
                'data' => 'file',
                'action' => 'query',
                'filter' => 'policies.name eq "public"'
            ]),
        ]);
    }
}
