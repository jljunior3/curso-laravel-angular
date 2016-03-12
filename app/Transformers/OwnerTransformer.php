<?php

namespace CodeProject\Transformers;

use CodeProject\Entities\User;
use League\Fractal\TransformerAbstract;

class OwnerTransformer extends TransformerAbstract
{
    /**
     * @param User $owner
     * @return array
     */
    public function transform(User $owner)
    {
        return [
            'id'   => $owner->id,
            'name' => $owner->name
        ];
    }
}