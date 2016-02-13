<?php

namespace CodeProject\Transformers;

use CodeProject\Entities\Project;
use League\Fractal\TransformerAbstract;

class ProjectTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['members', 'client', 'owner'];

    /**
     * @param Project $project
     * @return array
     */
    public function transform(Project $project)
    {
        return [
            'project_id' => $project->id,
            'client_id' => $project->client_id,
            'owner_id' => $project->owner_id,
            'name' => $project->name,
            'description' => $project->description,
            'progress' => $project->progress,
            'status' => $project->status,
            'due_date' => $project->due_date
        ];
    }

    /**
     * @param Project $project
     * @return \League\Fractal\Resource\Collection
     */
    public function includeMembers(Project $project)
    {
        return $this->collection($project->members, new ProjectMemberTransformer());
    }

    /**
     * @param Project $project
     * @return \League\Fractal\Resource\Item
     */
    public function includeClient(Project $project)
    {
        if (!is_null($project->client)) {
            return $this->item($project->client, new ClientTransformer());
        }
    }

    /**
     * @param Project $project
     * @return \League\Fractal\Resource\Item
     */
    public function includeOwner(Project $project)
    {
        if (!is_null($project->owner)) {
            return $this->item($project->owner, new OwnerTransformer());
        }
    }
}