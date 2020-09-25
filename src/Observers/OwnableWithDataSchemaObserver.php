<?php

namespace Mesolite\Observers;

use Amethyst\DataSchema\Helper;
use Amethyst\DataSchema\Manager;
use Amethyst\Models\DataSchema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Yaml\Yaml;
use Amethyst\Core\Attributes\DataNameAttribute;
use Illuminate\Container\Container;
use Amethyst\Models\RelationSchema;

class OwnableWithDataSchemaObserver
{
    /**
     * @var \Railken\Lem\Contracts\ManagerContract
     */
    protected $manager;

    /**
     * Create a new instance
     *
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->manager = $app->get('amethyst')->get('relation-schema');
    }

    /**
     * Get relation schema by name data
     *
     * @param string $data
     *
     * @return RelationSchema
     */
    public function getRelation(string $data): ?RelationSchema
    {
        return $this->manager->getRepository()->newQuery()->where([
            'data' => $data,
            'name' => 'ownables'
        ])->first();
    }

    /**
     * Handle the DataSchema "created" event.
     *
     * @param \Amethyst\Models\DataSchema $dataSchema
     */
    public function created(DataSchema $dataSchema)
    {
        $relation = $this->getRelation($dataSchema->name);

        if ($relation) {
            return;
        }

        $this->manager->createOrFail([
            'name'   => 'ownables',
            'type'   => 'MorphMany',
            'data' => $dataSchema->name,
            'payload' => Yaml::dump([
                'target' => 'ownable'
            ])
        ]);
    }

    /**
     * Handle the DataSchema "updated" event.
     *
     * @param \Amethyst\Models\DataSchema $dataSchema
     */
    public function updated(DataSchema $dataSchema)
    {
        $oldName = $dataSchema->getOriginal()['name'];

        if ($dataSchema->name !== $oldName) {

            $relation = $this->getRelation($oldName);

            if ($relation) {
                $this->manager->updateOrFail($relation, [
                    'name' => $dataSchema->name
                ]);
            }
        }
    }

    /**
     * Handle the DataSchema "deleted" event.
     *
     * @param \Amethyst\Models\DataSchema $dataSchema
     */
    public function deleted(DataSchema $dataSchema)
    {
        $relation = $this->getRelation($dataSchema->name);

        if ($relation) {
            $this->manager->delete($relation);
        }
    }
}
