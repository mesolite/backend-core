<?php

namespace Mesolite\Database\Seeds;

use Illuminate\Database\Seeder;
use Amethyst\Models\RelationSchema;
use Symfony\Component\Yaml\Yaml;

class WorkflowDoneNotification extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $workflow = app('amethyst')->get('workflow')->createOrFail([
            'name' => 'notify-user-on-complete',
            'autostart' => 1
        ])->getResource();
        
        $node = $workflow->next('listener', [
            'event' => \Amethyst\Events\WorkflowDone::class,
        ], [
            'event' => 'event'
        ]);

        $node1 = $workflow->new('notification', [
            'agent' => [
                'data' => 'user',
                'filter' => 'id = {{ event.user.id }}'
            ],
            'message'    => 'Job `{{ event.workflowState.workflow.name }}` is complete',
            'vars' => [
                'url' => '{{ event.data.file }}'
            ]
        ]);

        $switch = $node->next('switcher', [
            'channels' => [
                $node1->id => '"{{ event.workflowState.workflow.name }}" !== "notify-user-on-complete"',
            ]
        ], [
            'event' => 'event'
        ]);

        $switch->relations()->attach($node1);
    }
}
