<?php

namespace Mesolite\Database\Seeds;

use Illuminate\Database\Seeder;
use Amethyst\Models\RelationSchema;
use Symfony\Component\Yaml\Yaml;
use Amethyst\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('amethyst')->get('permission')->findOrCreateOrFail([
            'type' => 'route',
            'effect' => 'allow',
            'payload' => Yaml::dump([
                'url' => [
                    '/',
                    '/api',
                    '/api/auth',
                    '/api/auth/(.*)',
                    '/oauth/(.*)',
                    '/broadcasting/auth'
                ]
            ])
        ]);

        // allow everything user id 1
        app('amethyst')->get('permission')->findOrCreate([
            'effect'  => 'allow',
            'type'    => 'data',
            'payload' => Yaml::dump([
                'data'   => '*',
                'action' => '*',
            ]),
            'agent' => '{{ agent.id }} == 1',
        ])->getResource();

        app('amethyst')->get('permission')->findOrCreateOrFail([
            'type' => 'route',
            'effect' => 'allow',
            'payload' => Yaml::dump([
                'url' => '*'
            ]),
            'agent' => "{{ agent.id }} == 1"
        ]);

        app('amethyst')->get('permission')->findOrCreate([
            'effect'  => 'allow',
            'type'    => 'data',
            'payload' => Yaml::dump([
                'data'   => '*',
                'action' => '*',
            ]),
            'agent' => "{{ agent.groups.where('name', 'admin').count() }} > 0",
        ])->getResource();
        
        app('amethyst')->get('permission')->findOrCreateOrFail([
            'type' => 'route',
            'effect' => 'allow',
            'payload' => Yaml::dump([
                'url' => '*'
            ]),
            'agent' => "{{ agent.groups.where('name', 'admin').count() }} > 0"
        ]);

        app('amethyst')->get('permission')->findOrCreateOrFail([
            'type' => 'route',
            'effect' => 'allow',
            'payload' => Yaml::dump([
                'url' => '*'
            ]),
            'agent' => "{{ agent.groups.where('name', 'can-access-admin').count() }} > 0"
        ]);

        RelationSchema::firstOrCreate([
            'name'   => 'groups',
            'type'   => 'MorphToMany',
            'data' => 'user',
            'payload' => Yaml::dump([
                'target' => 'group',
                'key' => 'user-group',
                'inversedBy' => 'users'
            ])
        ]);

        RelationSchema::firstOrCreate([
            'name'   => 'users',
            'type'   => 'MorphToMany',
            'data' => 'group',
            'payload' => Yaml::dump([
                'target' => 'user',
                'key' => 'user-group',
                'inverse' => true,
                'inversedBy' => 'groups'
            ])
        ]);

        $group = \Amethyst\Models\Group::create([
            'name' => 'admin'
        ]);

        $group = \Amethyst\Models\Group::create([
            'name' => 'can-access-admin'
        ]);

        app('amethyst')->getData()->map(function ($data, $key) {
            RelationSchema::firstOrCreate([
                'name'   => 'ownables',
                'type'   => 'MorphMany',
                'data' => $key,
                'payload' => Yaml::dump([
                    'target' => 'ownable'
                ])
            ]);
        });

        RelationSchema::firstOrCreate([
            'name'   => 'ownership',
            'type'   => 'MorphMany',
            'data' => 'user',
            'payload' => Yaml::dump([
                'target' => 'ownable',
                'inverse' => true,
                'inversedBy' => 'ownables',
                'morphType' => 'owner'
            ])
        ]);

        app('amethyst')->get('permission')->createOrFail([
            'effect'  => 'allow',
            'type'    => 'data',
            'payload' => Yaml::dump([
                'data'   => '*',
                'action' => 'query',
                'filter' => 'ownables.owner_id = {{ agent.id }}',
            ]),
        ]);
    }
}
