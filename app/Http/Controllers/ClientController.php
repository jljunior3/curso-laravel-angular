<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Presenters\ClientPresenter;
use CodeProject\Repositories\ClientRepository;
use CodeProject\Services\ClientService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * @var ClientRepository
     */
    private $repository;
    private $repositoryPresenter;

    /**
     * @var ClientService
     */
    private $service;

    public function __construct(ClientRepository $repository, ClientService $service)
    {
        $this->repository = $repository;
        $this->repositoryPresenter = $repository->setPresenter(ClientPresenter::class);
        $this->service = $service;
    }

    public function index()
    {
        return $this->repositoryPresenter->all();
    }

    public function store(Request $request)
    {
        return $this->service->create($request->all());
    }

    public function show($id)
    {
        try {
            return $this->repositoryPresenter->find($id);
        } catch (ModelNotFoundException $e) {
            return ['status' => 'error', 'message' => 'Cliente não encontrado.'];
        }
    }

    public function update(Request $request, $id)
    {
        try {
            return $this->service->update($request->all(), $id);
        } catch (ModelNotFoundException $e) {
            return ['status' => 'error', 'message' => 'Cliente não encontrado.'];
        }
    }

    public function destroy($id)
    {
        try {
            $this->repository->delete($id);
            return ['status' => 'success', 'message' => 'Cliente deletado com sucesso!'];
        } catch (ModelNotFoundException $e) {
            return ['status' => 'error', 'message' => 'Cliente não encontrado.'];
        } catch (QueryException $e) {
            return ['status' => 'error', 'message' => 'Este cliente não pode ser apagado, pois existe um ou mais projetos vinculados a ele.'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Ocorreu algum erro ao deletar o cliente.'];
        }
    }
}
