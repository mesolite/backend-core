<?php

namespace Mesolite\Database\Seeds;

use Illuminate\Database\Seeder;
use Symfony\Component\Yaml\Yaml;
use Railken\Lem\Attributes;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attribute = new Attributes\TextAttribute('file');
        $attribute->setType('File');
        $attribute->setRequired(false);
        $attribute->setManager(app('amethyst')->get('file'));

        app('amethyst.data-view')->createAttribute(app('amethyst')->get('file'), $attribute);

        // Allow anyone to see the route file stream
        app('amethyst')->get('permission')->findOrCreateOrFail([
            'type' => 'route',
            'effect' => 'allow',
            'payload' => Yaml::dump([
                'url' => '/api/data/file/stream/(.*)'
            ]),
        ]);

        // Allow anyone to see files that have public set to 1
        app('amethyst')->get('permission')->findOrCreateOrFail([
            'type' => 'data',
            'effect' => 'allow',
            'payload' => Yaml::dump([
                'data' => 'file',
                'action' => 'query',
                'filter' => 'public eq 1'
            ]),
        ]);
    }
}
