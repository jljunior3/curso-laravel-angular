<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectRepository;
use CodeProject\Services\ProjectService;
use Illuminate\Http\Request;

//use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class ProjectController extends Controller
{
    /**
     * @var ProjectRepository
     */
    private $repository;

    /**
     * @var ProjectService
     */
    private $service;

    public function __construct(ProjectRepository $repository, ProjectService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function index()
    {
        return $this->repository->findWhere(['owner_id' => $this->getOwnerId()]);
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

        return $this->repository->find($id);
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
        return $this->repository->isOwner($projectId, $this->getOwnerId());
    }

    private function checkProjectMember($projectId)
    {
        return $this->repository->hasMember($projectId, $this->getOwnerId());
    }

    private function checkProjectPermissions($projectId)
    {
        return $this->checkProjectOwner($projectId) || $this->checkProjectMember($projectId);
    }
}
