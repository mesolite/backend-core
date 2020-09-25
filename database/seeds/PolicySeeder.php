<?php

namespace Mesolite\Database\Seeds;

use Illuminate\Database\Seeder;
use Symfony\Component\Yaml\Yaml;
use Railken\Lem\Attributes;

class PolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        app('amethyst')->get('data-schema')->createOrFail([
            'name' => 'policy'
        ]);

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'policy',
            'name'   => 'name',
            'schema' => 'Text',
            'required' => true
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'policy',
            'name'   => 'description',
            'schema' => 'LongText'
        ])->getResource();
        
        app('amethyst')->get('policy')->createOrFail([
            'name' => 'public',
            'description' => 'When this policy is added to a Resource, it means that the Resource should be visible by anyone, even not logged'
        ])->getResource();
    }
}
