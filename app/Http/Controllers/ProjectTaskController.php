<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Services\ProjectTaskService;
use Illuminate\Http\Request;

class ProjectTaskController extends Controller
{
    //    /**
    //     * @var ProjectTaskRepository
    //     */
    //    private $repository;
    //    private $repositoryPresenter;

    /**
     * @var ProjectTaskService
     */
    private $service;

    public function __construct(ProjectTaskService $service)
    {
        //        $this->repository = $repository;
        //        $this->repositoryPresenter = $repository->setPresenter(ProjectTaskPresenter::class);
        $this->service = $service;

        $this->middleware('check-project-permissions', ['except' => ['show', 'index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($projectId)
    {
        return $this->service->all($projectId);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request, $projectId)
    {
        $data               = $request->all();
        $data['project_id'] = $projectId;
        return $this->service->create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($projectId, $id)
    {
        return $this->service->find($projectId, $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $projectId, $id)
    {
        $data               = $request->all();
        $data['project_id'] = $projectId;
        return $this->service->update($data, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($projectId, $id)
    {
        return $this->service->delete($projectId, $id);
    }

    //    public function index($id)
    //    {
    //        return $this->repositoryPresenter->findWhere(['project_id' => $id]);
    //    }
    //
    //    public function store(Request $request, $id)
    //    {
    //        return $this->service->create($request->all() + ['project_id' => $id]);
    //    }
    //
    //    public function show($id, $taskId)
    //    {
    //        try {
    //            $tasks = $this->repositoryPresenter->findWhere(['project_id' => $id, 'id' => $taskId]);
    //
    //            if (!isset($tasks[0])) throw new ModelNotFoundException;
    //
    //            return $tasks;
    //        } catch (ModelNotFoundException $e) {
    //            return ['status' => 'error', 'message' => 'Tarefa não encontrada.'];
    //        }
    //    }
    //
    //    public function update(Request $request, $id, $taskId)
    //    {
    //        return $this->service->update($request->all(), $taskId);
    //    }
    //
    //    public function destroy($id, $taskId)
    //    {
    //        return $this->service->delete($taskId);
    //    }
}
