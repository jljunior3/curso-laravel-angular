<?php

namespace CodeProject\Transformers;

use CodeProject\Entities\ProjectTask;
use League\Fractal\TransformerAbstract;

class ProjectTaskTransformer extends TransformerAbstract
{
    /**
     * @param ProjectTask $projectTask
     * @return array
     */
    public function transform(ProjectTask $projectTask)
    {
        return [
            'id'         => $projectTask->id,
            'project_id' => $projectTask->project_id,
            'name'       => $projectTask->name,
            'start_date' => $projectTask->start_date,
            'due_date'   => $projectTask->due_date,
            'status'     => $projectTask->status
        ];
    }
}