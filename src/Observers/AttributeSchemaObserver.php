<?php

namespace Mesolite\Observers;

use Amethyst\AttributeSchema\Helper;
use Amethyst\AttributeSchema\Manager;
use Amethyst\Models\AttributeSchema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Yaml\Yaml;

class AttributeSchemaObserver
{
    /**
     * Handle the AttributeSchema "created" event.
     *
     * @param \Amethyst\Models\AttributeSchema $attributeSchema
     */
    public function created(AttributeSchema $attributeSchema)
    {
        app('amethyst.data-view')->createAttributeByName($attributeSchema->model, $attributeSchema->name);
    }

    /**
     * Handle the AttributeSchema "updated" event.
     *
     * @param \Amethyst\Models\AttributeSchema $attributeSchema
     */
    public function updated(AttributeSchema $attributeSchema)
    {
        $oldName = $attributeSchema->getOriginal()['name'];

        if ($attributeSchema->name !== $oldName) {
            app('amethyst.data-view')->renameAttributeByName($attributeSchema->model, $oldName, $attributeSchema->name);

        }

        $fields = ['required', 'options'];

        foreach ($fields as $field) {

            $oldField = $attributeSchema->getOriginal()[$field];

            if ($attributeSchema->$field !== $oldField) {
                app('amethyst.data-view')->regenerateAttributeByName($attributeSchema->model, $attributeSchema->name);
            }
        }
    }

    /**
     * Handle the AttributeSchema "deleted" event.
     *
     * @param \Amethyst\Models\AttributeSchema $attributeSchema
     */
    public function deleted(AttributeSchema $attributeSchema)
    {
        app('amethyst.data-view')->removeAttributeByName($attributeSchema->model, $attributeSchema->name);
    }

   
}
