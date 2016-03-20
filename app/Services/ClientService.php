<?php

namespace CodeProject\Services;

use CodeProject\Presenters\ClientPresenter;
use CodeProject\Repositories\ClientRepository;
use CodeProject\Validators\ClientValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
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
            $result = $this->repository->setPresenter($this->presenter)->paginate($limit);

            //print_r($result); die;

            return $result;
        } catch (\Exception $e) {
            return [
                "error"      => true,
                "message"    => 'Nenhum registro encontrado.',
                "messageDev" => $e->getMessage()
            ];
        }
    }

    public function find($id)
    {
        try {
            return $this->repository->setPresenter($this->presenter)->find($id);
        } catch (\Exception $e) {
            return [
                "error"      => true,
                "message"    => 'Registro não encontrado.',
                "messageDev" => $e->getMessage()
            ];
        }
    }

    public function delete($id)
    {
        try {
            $this->repository->delete($id);
            return [
                'success' => true,
                "message" => 'Registro excluído com sucesso.'
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'error'      => true,
                'message'    => 'Registro não encontrado.',
                "messageDev" => $e->getMessage()
            ];
        } catch (QueryException $e) {
            return [
                'error'      => true,
                'message'    => 'Este registro não pode ser excluído, pois existe um ou mais projetos vinculados a ele.',
                "messageDev" => $e->getMessage()
            ];
        } catch (\Exception $e) {
            return [
                "error"      => true,
                "message"    => 'Falha ao excluir registro.',
                "messageDev" => $e->getMessage()
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
                'error'      => true,
                'message'    => $e->getMessageBag(),
                "messageDev" => 'ValidatorException'
            ];
        } catch (\Exception $e) {
            return [
                "error"      => true,
                "message"    => 'Falha ao gravar registro.',
                "messageDev" => $e->getMessage()
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
                'error'      => true,
                'message'    => $e->getMessageBag(),
                "messageDev" => 'ValidatorException'
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'error'      => true,
                'message'    => 'Registro não encontrado.',
                "messageDev" => $e->getMessage()
            ];
        } catch (\Exception $e) {
            return [
                "error"      => true,
                "message"    => 'Falha ao atualizar dados.',
                "messageDev" => $e->getMessage()
            ];
        }
    }
}