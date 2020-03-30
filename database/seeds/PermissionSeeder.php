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

        app('amethyst')->get('permission')->findOrCreateOrFail([
            'type' => 'route',
            'effect' => 'allow',
            'payload' => Yaml::dump([
                'url' => '*'
            ]),
            'agent' => "{{ agent.id }} == 1"
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
            'name' => 'can-access-admin'
        ]);

        \App\Models\User::where('id', 1)->first()->groups()->attach($group->id);

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
    }
}
