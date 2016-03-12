<?php

namespace CodeProject\Repositories;

use CodeProject\Entities\Project;
//use CodeProject\Presenters\ProjectPresenter;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ProjectRepositoryEloquent
 * @package namespace CodeProject\Repositories;
 */
class ProjectRepositoryEloquent extends BaseRepository implements ProjectRepository
{
    /**
     * Specify Model class name
     * @return string
     */
    public function model()
    {
        return Project::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

//    public function hasMember($projectId, $memberId)
//    {
//        $project = $this->find($projectId);
//
//        foreach ($project->members as $member) {
//            if ($member->id == $memberId) {
//                return true;
//            }
//        }
//
//        return false;
//    }

    public function isOwner($projectId, $userId)
    {
        if (count($this->findWhere(['id' => $projectId, 'owner_id' => $userId]))) {
            return true;
        }

        return false;
    }

    public function isMember($projectId, $userId)
    {
        if ($this->find($projectId)->members()->find($userId)) {
            return true;
        }

        return false;
    }

    public function addMember($projectId, $memberId)
    {
        $project = $this->find($projectId);

        if ($project->members->contains('id', $memberId)) {
            return false;
        }

        $project->members()->attach($memberId);

        return true;
    }

    public function removeMember($projectId, $memberId)
    {
        if ($this->find($projectId)->members()->detach($memberId)) {
            return true;
        }

        return false;
    }

    public function findWithOwnerAndMember($userId, $limit = null, $columns = [])
    {
        return $this->scopeQuery(function ($query) use ($userId) {
            return $query
                ->select('projects.*')
                ->leftJoin('project_members', 'project_members.project_id', '=', 'projects.id')
                ->where('projects.owner_id', '=', $userId)
                ->orWhere('project_members.member_id', '=', $userId)
                ->groupBy('projects.id');
        })->paginate($limit, $columns);
    }
}
