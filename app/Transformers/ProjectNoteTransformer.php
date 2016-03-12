<?php

namespace CodeProject\Transformers;

use CodeProject\Entities\ProjectNote;
use League\Fractal\TransformerAbstract;

class ProjectNoteTransformer extends TransformerAbstract
{
    /**
     * @param ProjectNote $projectNote
     * @return array
     */
    public function transform(ProjectNote $projectNote)
    {
        return [
            'id'         => $projectNote->id,
            'project_id' => $projectNote->project_id,
            'title'      => $projectNote->title,
            'note'       => $projectNote->note
        ];
    }
}