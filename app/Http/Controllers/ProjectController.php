<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Presenters\ProjectPresenter;
use CodeProject\Repositories\ProjectRepository;
use CodeProject\Services\ProjectService;
use Illuminate\Http\Request;

use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class ProjectController extends Controller
{
    /**
     * @var ProjectRepository
     */
    private $repository;
    private $repositoryPresenter;

    /**
     * @var ProjectService
     */
    private $service;

    public function __construct(ProjectRepository $repository, ProjectService $service)
    {
        $this->repository = $repository;
        $this->repositoryPresenter = $repository->setPresenter(ProjectPresenter::class);
        $this->service = $service;
    }

    public function index()
    {
        return $this->repositoryPresenter->findWhere(['owner_id' => $this->getOwnerId()]);
        //return $this->repositoryPresenter->all();
    }

    public function store(Request $request)
    {
        return $this->service->create($request->all());
    }

    public function show($id)
    {
        if (!$this->checkProjectPermissions($id)) {
            return ['error' => 'Access Forbidden'];
        }

        return $this->repositoryPresenter->find($id);
    }

    public function update(Request $request, $id)
    {
        if (!$this->checkProjectPermissions($id)) {
            return ['error' => 'Access Forbidden'];
        }

        return $this->service->update($request->all(), $id);
    }

    public function destroy($id)
    {
        if (!$this->checkProjectOwner($id)) {
            return ['error' => 'Access Forbidden'];
        }

        $this->repository->delete($id);
    }

    private function getOwnerId()
    {
        return \Authorizer::getResourceOwnerId();
    }

    private function checkProjectOwner($projectId)
    {
        return $this->repository->skipPresenter()->isOwner($projectId, $this->getOwnerId());
    }

    private function checkProjectMember($projectId)
    {
        return $this->repository->skipPresenter()->hasMember($projectId, $this->getOwnerId());
    }

    private function checkProjectPermissions($projectId)
    {
        return $this->checkProjectOwner($projectId) || $this->checkProjectMember($projectId);
    }
}
