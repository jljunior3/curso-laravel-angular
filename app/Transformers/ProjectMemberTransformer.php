<?php

namespace CodeProject\Transformers;

use CodeProject\Entities\ProjectMember;
use CodeProject\Entities\User;
use League\Fractal\TransformerAbstract;

class ProjectMemberTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['member'];

    /**
     * @param ProjectMember $projectMember
     * @return array
     */
    public function transform(ProjectMember $projectMember)
    {
        return [
            'project_id' => $projectMember->project_id,
            'member_id'  => $projectMember->member_id
        ];
    }

    /**
     * @param ProjectMember $projectMember
     * @return \League\Fractal\Resource\Item
     */
    public function includeMember(ProjectMember $projectMember)
    {
        if (!is_null($projectMember->member)) {
            return $this->item($projectMember->member, new MemberTransformer());
        }
    }
}