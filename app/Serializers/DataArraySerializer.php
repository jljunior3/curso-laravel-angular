<?php

namespace CodeProject\Serializers;

use League\Fractal\Serializer\ArraySerializer;

class DataArraySerializer extends ArraySerializer
{
    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data)
    {

        //print_r($data);
        $aa = $this->test($data);

        echo '**********';
        print_r($aa);

        return ['data' => $data];
    }

    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array $data
     *
     * @return array
     */
    public function item($resourceKey, array $data)
    {
        return ['data' => $data];
    }

    private function test(array $array)
    {
        $result = [];

        foreach ($array as $key => $value) {


            if (isset($value['data'])) {
                echo '==>' . $key;
                $result[$key] = [];//$this->test($value['data']);
            }
            else if (is_array($value)) {
                $result[$key] = $this->test($value);
            }

            $result[$key] = $value;
        }

        return $result;
    }
}