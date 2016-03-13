<?php

namespace CodeProject\Services;

use CodeProject\Presenters\ProjectPresenter;
use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectService
{
    /**
     * @var ProjectRepository
     */
    protected $repository;

    /**
     * @var ProjectPresenter
     */
    private $presenter;

    /**
     * @var ProjectValidator
     */

    private $validator;

    public function __construct(ProjectRepository $repository, ProjectPresenter $presenter, ProjectValidator $validator)
    {
        $this->repository = $repository;
        $this->presenter  = $presenter;
        $this->validator  = $validator;
    }

    public function all($limit = null)
    {
        try {
            return $this->repository
                ->setPresenter($this->presenter)
                ->findWithOwnerAndMember(\Authorizer::getResourceOwnerId(), $limit);
        } catch (\Exception $e) {
            return [
                "error"   => true,
                "message"    => 'Nenhum registro encontrado.',
                "messageDev" => $e->getMessage()
            ];
        }
    }

    public function find($id)
    {
        try {
            return $this->repository
                ->setPresenter($this->presenter)
                ->with(['owner', 'client', 'members'])->find($id);
        } catch (\Exception $e) {
            return [
                "error"   => true,
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