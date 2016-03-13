<?php

namespace CodeProject\Services;

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
     * @var ProjectValidator
     */

    private $validator;

    public function __construct(
        ProjectRepository $projectRepository, ProjectMemberRepository $memberRepository,
        ProjectMemberValidator $validator)
    {
        $this->projectRepository = $projectRepository;
        $this->memberRepository  = $memberRepository;
        $this->validator         = $validator;
    }

    public function getMembers($projectId)
    {
        try {
            return $this->memberRepository->getMembers($projectId);
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
            $members = $this->memberRepository->findWhere(['project_id' => $projectId, 'member_id' => $id]);

            if (isset($members[0])) {
                return $members[0];
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

    public function addMember($data)
    {
        try {
            $this->validator->with($data)->passesOrFail();
            $result = $this->projectRepository->addMember($data['project_id'], $data['user_id']);

            return [
                'success' => $result,
                "message" => 'Membro adicionado com sucesso.'
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
                "message"    => 'Falha ao adicionar membro.',
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
                "message" => 'Membro removido com sucesso.'
            ];
        } catch (\Exception $e) {
            return [
                "error"   => true,
                "message" => 'Falha ao remover membro.',
                "messageDev" => $e->getMessage()
            ];
        }
    }
}