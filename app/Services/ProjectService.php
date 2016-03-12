<?php

namespace CodeProject\Services;

use CodeProject\Presenters\ProjectPresenter;
use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectValidator;
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
                "message" => $e->getMessage()
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
    //            return ['status' => 'success', 'message' => 'Projeto criado com sucesso.'];
    //        } catch (ValidatorException $e) {
    //            return ['status' => 'error', 'errors' => $e->getMessageBag()];
    //        } catch (ErrorException $e) {
    //            return ['status' => 'error', 'message' => 'Falha ao gravar projeto.'];
    //        }
    //    }
    //
    //    public function update(array $data, $id)
    //    {
    //        try {
    //            $this->validator->with($data)->passesOrFail();
    //            $this->repository->find($id) && $this->repository->update($data, $id);
    //
    //            return ['status' => 'success', 'message' => 'Projeto atualizado com sucesso.'];
    //        } catch (ValidatorException $e) {
    //            return ['status' => 'error', 'message' => $e->getMessageBag()];
    //        } catch (ModelNotFoundException $e) {
    //            return ['status' => 'error', 'message' => 'Projeto não encontrado.'];
    //        } catch (ErrorException $e) {
    //            return ['status' => 'error', 'message' => 'Falha ao atualizar projeto.'];
    //        }
    //    }
    //
    //    public function delete($id)
    //    {
    //        try {
    //            $this->repository->delete($id);
    //            return ['status' => 'success', 'message' => 'Projeto deletado com sucesso.'];
    //        } catch (ModelNotFoundException $e) {
    //            return ['status' => 'error', 'message' => 'Projeto não encontrado.'];
    //        } catch (QueryException $e) {
    //            return [
    //                'status' => 'error',
    //                'message' => 'Este projeto não pode ser apagado, pois existe um ou mais clientes vinculados a ele.'
    //            ];
    //        } catch (ErrorException $e) {
    //            return ['status' => 'error', 'message' => 'Falha ao deletar projeto.'];
    //        }
    //    }
    //
    //    public function createFile(array $data)
    //    {
    //        try {
    //            $project = $this->repository->find($data['project_id']);
    //            $projectFile = $project->files()->create($data);
    //
    //            $this->storage->put($projectFile->id . '.' . $data['extension'], $this->filesystem->get($data['file']));
    //
    //            return ['status' => 'success', 'message' => 'Arquivo criado com sucesso.'];
    //        } catch (ErrorException $e) {
    //            return ['status' => 'error', 'message' => 'Falha ao gravar arquivo.'];
    //        }
    //    }
    //
    //    /*
    //
    //    - addMember: para adicionar um novo member em um projeto
    //    - removeMember: para remover um membro de um projeto
    //    - isMember: para verificar se um usuário é membro de um determinado projeto
    //
    //     */
    //
    //    public function addMember($id, $memberId)
    //    {
    //        try {
    //            $project = $this->repository->find($id);
    //            $member = $this->userRepository->find($memberId);
    //
    //            $project->members()->associate($member->id);
    //            //$project->members()->attach($member->id);
    //
    //            return ['status' => 'success', 'message' => 'Membro adicionado ao projeto com sucesso.'];
    //        } catch (ErrorException $e) {
    //            return ['status' => 'error', 'message' => 'Falha ao adicionar membro ao projeto.'];
    //        }
    //    }
    //
    //    public function removeMember($id, $memberId)
    //    {
    //        try {
    //            $project = $this->repository->find($id);
    //            $member = $this->userRepository->find($memberId);
    //
    //            $project->members()->dissociate($member->id);
    //            //$project->members()->detach($member->id);
    //
    //            return ['status' => 'success', 'message' => 'Membro deletado do projeto com sucesso.'];
    //        } catch (ErrorException $e) {
    //            return ['status' => 'error', 'message' => 'Falha ao deletar membro do projeto.'];
    //        }
    //    }
    //
    //    public function isMember($id, $memberId)
    //    {
    //        return $this->repository->hasMember($id, $memberId);
    //    }
}