<?php

namespace CodeProject\Services;

use CodeProject\Presenters\ProjectNotePresenter;
use CodeProject\Repositories\ProjectNoteRepository;
use CodeProject\Validators\ProjectNoteValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
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
                "error"      => true,
                "message"    => 'Nenhum registro encontrado.',
                "messageDev" => $e->getMessage()
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
                "error"      => true,
                "message"    => 'Registro não encontrado.',
                "messageDev" => $e->getMessage()
            ];
        }
    }

    public function delete($projectId, $id)
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