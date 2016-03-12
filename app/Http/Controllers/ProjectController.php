<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
//    /**
//     * @var ProjectRepository
//     */
//    private $repository;
//    private $repositoryPresenter;

    /**
     * @var ProjectService
     */
    private $service;

    public function __construct(ProjectService $service)
    {
//        $this->repository          = $repository;
//        $this->repositoryPresenter = $repository->setPresenter(ProjectPresenter::class);
//        $this->service             = $service;

        $this->service = $service;
        $this->middleware('check-project-owner', ['except' => ['index', 'store', 'show']]);
        $this->middleware('check-project-permissions', ['except' => ['index', 'store', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        return $this->service->all($request->query->get('limit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        return $this->service->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        return $this->service->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        return $this->service->update($request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        return $this->service->delete($id);
    }

    //    public function index()
    //    {
    //        return $this->repositoryPresenter->findWhere(['owner_id' => $this->getOwnerId()]);
    //        //return $this->repositoryPresenter->all();
    //    }
    //
    //    public function store(Request $request)
    //    {
    //        return $this->service->create($request->all());
    //    }
    //
    //    public function show($id)
    //    {
    //        if (!$this->checkProjectPermissions($id)) {
    //            return ['status' => 'error', 'message' => 'Access Forbidden'];
    //        }
    //
    //        try {
    //            return $this->repositoryPresenter->find($id);
    //        } catch (ModelNotFoundException $e) {
    //            return ['status' => 'error', 'message' => 'Projeto não encontrado.'];
    //        }
    //    }
    //
    //    public function update(Request $request, $id)
    //    {
    //        if (!$this->checkProjectPermissions($id)) {
    //            return ['status' => 'error', 'message' => 'Access Forbidden'];
    //        }
    //
    //        return $this->service->update($request->all(), $id);
    //    }
    //
    //    public function destroy($id)
    //    {
    //        if (!$this->checkProjectOwner($id)) {
    //            return ['status' => 'error', 'message' => 'Access Forbidden'];
    //        }
    //
    //        return $this->service->delete($id);
    //    }
    //
    //
    //    /*
    //     * Private functions
    //     */
    //
    //    private function getOwnerId()
    //    {
    //        return \Authorizer::getResourceOwnerId();
    //    }
    //
    //    private function checkProjectOwner($projectId)
    //    {
    //        return $this->repository->isOwner($projectId, $this->getOwnerId());
    //    }
    //
    //    private function checkProjectMember($projectId)
    //    {
    //        return $this->repository->hasMember($projectId, $this->getOwnerId());
    //    }
    //
    //    private function checkProjectPermissions($projectId)
    //    {
    //        return $this->checkProjectOwner($projectId) || $this->checkProjectMember($projectId);
    //    }
}
