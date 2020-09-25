<?php

namespace Mesolite\Database\Seeds;

use Illuminate\Database\Seeder;
use Symfony\Component\Yaml\Yaml;
use Railken\Lem\Attributes;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        app('amethyst')->get('data-schema')->createOrFail([
            'name' => 'group'
        ]);

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'group',
            'name'   => 'name',
            'schema' => 'Text',
            'required' => true
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'group',
            'name'   => 'description',
            'schema' => 'LongText'
        ])->getResource();
        
        app('amethyst')->get('group')->createOrFail([
            'name' => 'admin'
        ])->getResource();


        app('amethyst')->get('relation-schema')->createOrFail([
            'name'   => 'groups',
            'type'   => 'MorphToMany',
            'data' => 'user',
            'payload' => Yaml::dump([
                'target' => 'group',
                'key' => 'user-group',
                'inversedBy' => 'users'
            ])
        ]);

        app('amethyst')->get('relation-schema')->createOrFail([
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
    }
}
