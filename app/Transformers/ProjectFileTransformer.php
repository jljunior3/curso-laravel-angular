<?php

namespace CodeProject\Transformers;

use CodeProject\Entities\ProjectFile;
use League\Fractal\TransformerAbstract;

class ProjectFileTransformer extends TransformerAbstract
{
    /**
     * @param ProjectFile $model
     * @return array
     */
    public function transform(ProjectFile $model)
    {
        return [
            'project_id'  => $model->project_id,
            'id'          => $model->id,
            'name'        => $model->name,
            'extension'   => $model->extension,
            'description' => $model->description,
            //'created_at'  => $model->created_at->toDateTimeString(),
            //'updated_at'  => $model->updated_at->toDateTimeString()
        ];
    }
}