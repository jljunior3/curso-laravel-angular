<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Presenters\ProjectPresenter;
use CodeProject\Repositories\ProjectRepository;
use CodeProject\Services\ProjectService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

//use LucaDegasperi\OAuth2Server\Facades\Authorizer;

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
        //return $this->repositoryPresenter->findWhere(['owner_id' => $this->getOwnerId()]);
        return $this->repositoryPresenter->all();
    }

    public function store(Request $request)
    {
        return $this->service->create($request->all());
    }

    public function show($id)
    {
        /*if (!$this->checkProjectPermissions($id)) {
            return ['error' => 'Access Forbidden'];
        }*/

        try {
            return $this->repositoryPresenter->find($id);
        } catch (ModelNotFoundException $e) {
            return ['status' => 'error', 'message' => 'Projeto não encontrado.'];
        }
    }

    public function update(Request $request, $id)
    {
        /*if (!$this->checkProjectPermissions($id)) {
            return ['error' => 'Access Forbidden'];
        }*/

        try {
            return $this->service->update($request->all(), $id);
        } catch (ModelNotFoundException $e) {
            return ['status' => 'error', 'message' => 'Projeto não encontrado.'];
        }
    }

    public function destroy($id)
    {
        /*if (!$this->checkProjectOwner($id)) {
            return ['error' => 'Access Forbidden'];
        }*/

        try {
            $this->repository->delete($id);
            return ['status' => 'success', 'message' => 'Projeto deletado com sucesso!'];
        } catch (ModelNotFoundException $e) {
            return ['status' => 'error', 'message' => 'Projeto não encontrado.'];
        } catch (QueryException $e) {
            return ['status' => 'error', 'message' => 'Este projeto não pode ser apagado, pois existe um ou mais clientes vinculados a ele.'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Ocorreu algum erro ao deletar o projeto.'];
        }
    }

    /*private function getOwnerId()
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
    }*/
}
