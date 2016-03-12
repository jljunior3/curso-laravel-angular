<?php

namespace CodeProject\Services;

use CodeProject\Presenters\ProjectTaskPresenter;
use CodeProject\Repositories\ProjectTaskRepository;
use CodeProject\Validators\ProjectTaskValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectTaskService
{
    /**
     * @var ProjectTaskRepository
     */
    protected $repository;

    /**
     * @var ProjectTaskPresenter
     */
    private $presenter;

    /**
     * @var ProjectTaskValidator
     */
    private $validator;

    public function __construct(
        ProjectTaskRepository $repository, ProjectTaskPresenter $presenter,
        ProjectTaskValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->presenter  = $presenter;
    }

    public function all($projectId)
    {
        try {
            return $this->repository
                ->setPresenter($this->presenter)
                ->findWhere(['project_id' => $projectId]);
        } catch (\Exception $e) {
            return [
                "error"   => true,
                "message" => $e->getMessage()
            ];
        }
    }

    public function find($projectId, $id)
    {
        try {
            $tasks = (array) $this->repository
                ->setPresenter($this->presenter)
                ->findWhere(['project_id' => $projectId, 'id' => $id]);

            if (isset($tasks[0])) {
                return $tasks[0];
            }

            return [];
        } catch (\Exception $e) {
            return [
                "error"   => true,
                "message" => $e->getMessage()
            ];
        }
    }

    public function delete($projectId, $id)
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
    //            return ['status' => 'success', 'message' => 'Tarefa criada com sucesso.'];
    //        } catch (ValidatorException $e) {
    //            return ['status' => 'error', 'errors' => $e->getMessageBag()];
    //        } catch (ErrorException $e) {
    //            return ['status' => 'success', 'message' => 'Falha ao gravar tarefa.'];
    //        }
    //    }
    //
    //    public function update(array $data, $id)
    //    {
    //        try {
    //            $this->validator->with($data)->passesOrFail();
    //            $this->repository->update($data, $id);
    //
    //            return ['status' => 'success', 'message' => 'Tarefa atualizada com sucesso.'];
    //        } catch (ValidatorException $e) {
    //            return ['status' => 'error', 'message' => $e->getMessageBag()];
    //        } catch (ErrorException $e) {
    //            return ['status' => 'success', 'message' => 'Falha ao atualizar tarefa.'];
    //        }
    //    }
    //
    //    public function delete($noteId)
    //    {
    //        try {
    //            $this->repository->delete($noteId);
    //            return ['status' => 'success', 'message' => 'Tarefa deletada com sucesso.'];
    //        } catch (ModelNotFoundException $e) {
    //            return ['status' => 'error', 'message' => 'Tarefa não encontrada.'];
    //        } catch (QueryException $e) {
    //            return [
    //                'status' => 'error',
    //                'message' => 'Esta tarefa não pode ser deletada, pois existe um ou mais projetos vinculados a ela.'
    //            ];
    //        } catch (ErrorException $e) {
    //            return ['status' => 'error', 'message' => 'Falha ao deletar tarefa.'];
    //        }
    //    }
}