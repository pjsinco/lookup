<?php

namespace Elit\Transformers;

class SpecialtyTransformer extends Transformer
{

    /**
     * Customize the attribute names of a Location model.
     * 
     * @param array
     * @return array
     */
    public function transform($specialty)
    {
        return [
            'code' =>  $specialty['code'],
            'name' => $specialty['full'],
        ];
    }

}
