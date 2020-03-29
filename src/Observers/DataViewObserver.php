<?php

namespace Mesolite\Observers;

use Amethyst\Models\DataView;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Yaml\Yaml;
use Mesolite\Jobs\TriggerEvent;
use Mesolite\Events\DataViewFlush;

class DataViewObserver
{
    /**
     * Handle the DataView "saved" event.
     *
     * @param \Amethyst\Models\DataView $dataView
     */
    public function saved(DataView $dataView)
    {
        TriggerEvent::dispatch(new DataViewFlush)->delay(now()->addSeconds(5));
    }
}
