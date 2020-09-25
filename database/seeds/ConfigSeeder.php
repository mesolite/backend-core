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
        $resource = app('amethyst')->get('config')->createOrFail([
            'key' => 'app.name',
            'value' => 'Mesolite'
        ])->getResource();

        $resource->policies()->save(app('amethyst')->get('policy')->getRepository()->findOneBy(['name' => 'public']));

        $filename = resource_path("logo.png");

        $resource = app('amethyst')->get('file')->createOrFail([
            'name'   => 'app.'.basename($filename),
            'public' => 1
        ])->getResource();

        $resource
            ->addMedia($filename)
            ->preservingOriginal()
            ->toMediaCollection('app');

        $resource = app('amethyst')->get('config')->createOrFail([
            'key' => 'app.logo',
            'value' => $resource->getFullUrl()
        ])->getResource();

        $resource->policies()->save(app('amethyst')->get('policy')->getRepository()->findOneBy(['name' => 'public']));
    }
}
