<?php

namespace CodeProject\Services;

use CodeProject\Repositories\ProjectFileRepository;
use CodeProject\Validators\ProjectFileValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectFileService
{
    /**
     * @var ProjectRepository
     */

    private $repository;

    /**
     * @var ProjectValidator
     */

    private $validator;

    /**
     * @var Filesystem
     */

    private $filesystem;

    /**
     * @var Storage
     */

    private $storage;

    public function __construct(
        ProjectFileRepository $repository, ProjectFileValidator $validator,
        Filesystem $filesystem, Storage $storage)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->filesystem = $filesystem;
        $this->storage    = $storage;
    }

    public function getFiles($projectId)
    {
        try {
            return $this->repository->getFiles($projectId);
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
            $data = $this->repository->findWhere(['project_id' => $projectId, 'id' => $id]);

            if (isset($data['data']) && count($data['data'])) {
                return [
                    'data' => current($data['data'])
                ];
            }

            return $data;
        } catch (\Exception $e) {
            return [
                "error"      => true,
                "message"    => 'Registro não encontrado.',
                "messageDev" => $e->getMessage()
            ];
        }
    }

    public function create(array $data)
    {
        try {
            $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE);
            $projectFile = $this->repository->skipPresenter()->create($data);
            $this->storage->put($projectFile->getFileName(), $this->filesystem->get($data['file']));

            return ['success' => true];
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

    public function delete($projectId, $id)
    {
        try {
            $projectFile = $this->repository->find($id);

            if ($this->storage->exists($projectFile->getFileName())) {
                $this->storage->delete($projectFile->getFileName());
            }

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

    public function update(array $data, $id)
    {
        try {
            $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);
            return $this->repository->update($data, $id);
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

    public function getFilePath($id)
    {
        try {
            $projectFile = $this->repository->find($id);
            return $this->getBaseURL($projectFile);
        } catch (\Exception $e) {
            return [
                "error"   => true,
                "message" => $e->getMessage()
            ];
        }
    }

    public function getBaseURL($projectFile)
    {
        switch ($this->storage->getDefaultDriver()) {
            case 'local':
                return $this->storage->getDriver()->getAdapter()->getPathPrefix()
                . '/' . $projectFile->getFileName();
        }
    }

    public function getFileName($id)
    {
        $projectFile = $this->repository->find($id);
        return $projectFile->getFileName();
    }
}