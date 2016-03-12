<?php

namespace CodeProject\Repositories;

use CodeProject\Entities\ProjectFile;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ProjectFileRepositoryEloquent
 * @package namespace CodeProject\Repositories;
 */
class ProjectFileRepositoryEloquent extends BaseRepository implements ProjectFileRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ProjectFile::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getFiles($projectId)
    {
        return $this->findWhere(['project_id' => $projectId]);
    }
}
