<?php

namespace CodeProject\Transformers;

use CodeProject\Entities\Client;
use League\Fractal\TransformerAbstract;

class ClientTransformer extends TransformerAbstract
{
    /**
     * @param Client $client
     * @return array
     */
    public function transform(Client $client)
    {
        return [
            'name' => $client->name,
            'responsible' => $client->responsible,
            'email' => $client->email,
            'phone' => $client->phone,
            'address' => $client->address,
            'obs' => $client->obs
        ];
    }
}