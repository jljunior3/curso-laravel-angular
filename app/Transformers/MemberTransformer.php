<?php

namespace CodeProject\Transformers;

use CodeProject\Entities\User;
use League\Fractal\TransformerAbstract;

class MemberTransformer extends TransformerAbstract
{
    /**
     * @param User $member
     * @return array
     */
    public function transform(User $member)
    {
        return [
            'id'   => $member->id,
            'name' => $member->name
        ];
    }
}