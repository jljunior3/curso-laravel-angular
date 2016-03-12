<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Services\ClientService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    //    /**
    //     * @var ClientRepository
    //     */
    //    private $repository;
    //private $repositoryPresenter;

    /**
     * @var ClientService
     */
    private $service;

    public function __construct(ClientService $service)
    {
        //$this->repository = $repository;
        //$this->repositoryPresenter = $repository->setPresenter(ClientPresenter::class);
        $this->service = $service;
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
    //        return $this->repositoryPresenter->all();
    //    }
    //
    //    public function store(Request $request)
    //    {
    //        return $this->service->create($request->all());
    //    }
    //
    //    public function show($id)
    //    {
    //        try {
    //            return $this->repositoryPresenter->find($id);
    //        } catch (ModelNotFoundException $e) {
    //            return ['status' => 'error', 'message' => 'Cliente não encontrado.'];
    //        }
    //    }
    //
    //    public function update(Request $request, $id)
    //    {
    //        return $this->service->update($request->all(), $id);
    //    }
    //
    //    public function destroy($id)
    //    {
    //        return $this->service->delete($id);
    //    }
}
