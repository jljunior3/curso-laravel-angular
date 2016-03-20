<?php

namespace CodeProject\Services;

use CodeProject\Presenters\ProjectMemberPresenter;
use CodeProject\Repositories\ProjectMemberRepository;
use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectMemberValidator;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectMemberService
{
    /**
     * @var ProjectRepository
     */

    private $projectRepository;

    /**
     * @var ProjectMemberRepository
     */

    private $memberRepository;

    /**
     * @var ProjectMemberPresenter
     */
    private $memberPresenter;

    /**
     * @var ProjectValidator
     */

    private $validator;

    public function __construct(
        ProjectRepository $projectRepository, ProjectMemberRepository $memberRepository,
        ProjectMemberPresenter $memberPresenter,
        ProjectMemberValidator $validator)
    {
        $this->projectRepository = $projectRepository;
        $this->memberRepository  = $memberRepository;
        $this->memberPresenter   = $memberPresenter;
        $this->validator         = $validator;
    }

    public function getMembers($projectId)
    {
        try {
            return $this->memberRepository->setPresenter($this->memberPresenter)->getMembers($projectId);
        } catch (\Exception $e) {
            return [
                "error"      => true,
                "message"    => 'Nenhum registro encontrado.',
                "messageDev" => $e->getMessage()
            ];
        }
    }

    public function getMember($projectId, $id)
    {
        try {
            $data = $this->memberRepository
                ->setPresenter($this->memberPresenter)
                ->findWhere(['project_id' => $projectId, 'member_id' => $id]);


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

    public function addMember($data)
    {
        try {
            $this->validator->with($data)->passesOrFail();
            $result = $this->projectRepository->addMember($data['project_id'], $data['user_id']);

            return [
                'success' => $result,
                "message" => 'Adicionar membro ao projeto.'
            ];
        } catch (ValidatorException $e) {
            return [
                'error'      => true,
                'message'    => $e->getMessageBag(),
                "messageDev" => 'ValidatorException'
            ];
        } catch (\Exception $e) {
            return [
                "error"      => true,
                "message"    => 'Falha ao adicionar membro ao projeto.',
                "messageDev" => $e->getMessage()
            ];
        }
    }

    public function removeMember($projectId, $memberId)
    {
        try {
            $result = $this->projectRepository->removeMember($projectId, $memberId);

            return [
                'success' => $result,
                "message" => 'Remover membro do projeto.'
            ];
        } catch (\Exception $e) {
            return [
                "error"      => true,
                "message"    => 'Falha ao remover membro do projeto.',
                "messageDev" => $e->getMessage()
            ];
        }
    }
}