<?php

namespace CodeProject\Services;

use CodeProject\Presenters\ClientPresenter;
use CodeProject\Repositories\ClientRepository;
use CodeProject\Validators\ClientValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

class ClientService
{
    /**
     * @var ClientRepository
     */
    protected $repository;

    /**
     * @var ClientPresenter
     */
    private $presenter;

    /**
     * @var ClientValidator
     */
    private $validator;

    public function __construct(ClientRepository $repository, ClientPresenter $presenter, ClientValidator $validator)
    {
        $this->repository = $repository;
        $this->presenter  = $presenter;
        $this->validator  = $validator;
    }

    public function all($limit = null)
    {
        try {
            return $this->repository->setPresenter($this->presenter)->paginate($limit);
        } catch (\Exception $e) {
            return [
                "error"   => true,
                "message" => $e->getMessage()
            ];
        }
    }

    public function find($id)
    {
        try {
            return $this->repository->setPresenter($this->presenter)->find($id);
        } catch (\Exception $e) {
            return [
                "error"   => true,
                "message" => $e->getMessage()
            ];
        }
    }

    public function delete($id)
    {
        try {
            $this->repository->delete($id);
            return ['success' => true];
        } catch (\Exception $e) {
            return [
                "error"   => true,
                "message" => $e->getMessage()
            ];
        }
    }

    public function create(array $data)
    {
        try {
            $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE);
            return $this->repository->setPresenter($this->presenter)->create($data);
        } catch (ValidatorException $e) {
            return [
                'error'   => true,
                'message' => $e->getMessageBag()
            ];
        } catch (\Exception $e) {
            return [
                "error"   => true,
                "message" => $e->getMessage()
            ];
        }
    }

    public function update(array $data, $id)
    {
        try {
            $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);
            return $this->repository->setPresenter($this->presenter)->update($data, $id);

        } catch (ValidatorException $e) {
            return [
                'error'   => true,
                'message' => $e->getMessageBag()
            ];
        } catch (\Exception $e) {
            return [
                "error"   => true,
                "message" => $e->getMessage()
            ];
        }
    }


    //    public function create(array $data)
    //    {
    //        try {
    //            $this->validator->with($data)->passesOrFail();
    //            $this->repository->create($data);
    //
    //            return ['status' => 'success', 'message' => 'Cliente criado com sucesso.'];
    //        } catch (ValidatorException $e) {
    //            return ['status' => 'error', 'errors' => $e->getMessageBag()];
    //        } catch (ErrorException $e) {
    //            return ['status' => 'success', 'message' => 'Falha ao gravar cliente.'];
    //        }
    //    }
    //
    //    public function update(array $data, $id)
    //    {
    //        try {
    //            $this->validator->with($data)->passesOrFail();
    //            $this->repository->find($id) && $this->repository->update($data, $id);
    //
    //            return ['status' => 'success', 'message' => 'Cliente atualizado com sucesso.'];
    //        } catch (ValidatorException $e) {
    //            return ['status' => 'error', 'message' => $e->getMessageBag()];
    //        } catch (ModelNotFoundException $e) {
    //            return ['status' => 'error', 'message' => 'Cliente não encontrado.'];
    //        } catch (ErrorException $e) {
    //            return ['status' => 'success', 'message' => 'Falha ao atualizar cliente.'];
    //        }
    //    }
    //
    //    public function delete($id)
    //    {
    //        try {
    //            $this->repository->delete($id);
    //            return ['status' => 'success', 'message' => 'Cliente deletado com sucesso.'];
    //        } catch (ModelNotFoundException $e) {
    //            return ['status' => 'error', 'message' => 'Cliente não encontrado.'];
    //        } catch (QueryException $e) {
    //            return [
    //                'status'  => 'error',
    //                'message' => 'Este cliente não pode ser apagado, pois existe um ou mais projetos vinculados a ele.'
    //            ];
    //        } catch (ErrorException $e) {
    //            return ['status' => 'error', 'message' => 'Falha ao deletar cliente.'];
    //        }
    //    }
}