<?php

namespace CodeProject\Transformers;

use CodeProject\Entities\User;
use League\Fractal\TransformerAbstract;

class ClientTransformer extends TransformerAbstract
{
    public function transform(User $client)
    {
        return [
            'client_id' => $client->id,
            'name' => $client->name
        ];
    }
}