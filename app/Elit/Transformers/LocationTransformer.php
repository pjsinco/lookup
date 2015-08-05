<?php

namespace Elit\Transformers;

class LocationTransformer extends Transformer
{

    /**
     * Customize the attribute names of a Location model.
     * 
     * @param array
     * @return array
     */
    public function transform($location)
    {
        return [
            'zip' => $location['zip'],
            'city' => $location['city'],
            'state' => $location['state'],
            'lat' => $location['lat'],
            'lon' => $location['lon'],
        ];
    }

}
