<?php

namespace CodeProject\Services;

use CodeProject\Presenters\ProjectNotePresenter;
use CodeProject\Repositories\ProjectNoteRepository;
use CodeProject\Validators\ProjectNoteValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectNoteService
{
    /**
     * @var ProjectNoteRepository
     */
    protected $repository;

    /**
     * @var ProjectNotePresenter
     */
    private $presenter;

    /**
     * @var ProjectNoteValidator
     */
    private $validator;

    public function __construct(
        ProjectNoteRepository $repository, ProjectNotePresenter $presenter,
        ProjectNoteValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->presenter  = $presenter;
    }

    public function all($projectId)
    {
        try {
            return $this->repository->setPresenter($this->presenter)->findWhere(['project_id' => $projectId]);
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
            $notes = $this->repository
                ->setPresenter($this->presenter)
                ->findWhere(['project_id' => $projectId, 'id' => $id]);

            if (isset($notes[0])) {
                return $notes[0];
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
    //            return ['status' => 'success', 'message' => 'Nota criada com sucesso.'];
    //        } catch (ValidatorException $e) {
    //            return ['status' => 'error', 'errors' => $e->getMessageBag()];
    //        } catch (ErrorException $e) {
    //            return ['status' => 'success', 'message' => 'Falha ao gravar nota.'];
    //        }
    //    }
    //
    //    public function update(array $data, $id)
    //    {
    //        try {
    //            $this->validator->with($data)->passesOrFail();
    //            $this->repository->update($data, $id);
    //
    //            return ['status' => 'success', 'message' => 'Nota atualizada com sucesso.'];
    //        } catch (ValidatorException $e) {
    //            return ['status' => 'error', 'message' => $e->getMessageBag()];
    //        } catch (ErrorException $e) {
    //            return ['status' => 'success', 'message' => 'Falha ao atualizar nota.'];
    //        }
    //    }
    //
    //    public function delete($noteId)
    //    {
    //        try {
    //            $this->repository->delete($noteId);
    //            return ['status' => 'success', 'message' => 'Nota deletada com sucesso.'];
    //        } catch (ModelNotFoundException $e) {
    //            return ['status' => 'error', 'message' => 'Nota não encontrada.'];
    //        } catch (QueryException $e) {
    //            return [
    //                'status' => 'error',
    //                'message' => 'Esta nota não pode ser deletada, pois existe um ou mais projetos vinculados a ela.'
    //            ];
    //        } catch (ErrorException $e) {
    //            return ['status' => 'error', 'message' => 'Falha ao deletar nota.'];
    //        }
    //    }
}