<?php

namespace Elit\Transformers;

class PhysicianTransformer extends Transformer
{

    /**
     * Customize the attribute names of a Location model.
     * 
     * @param array
     * @return array
     */
    public function transform($phys)
    {
        return [
            'id' => $phys['id'],
            'full_name' => $phys['full_name'],
            'prefix' => $phys['prefix'],
            'first_name' => $phys['first_name'],
            'middle_name' => $phys['middle_name'],
            'last_name' => $phys['last_name'],
            'suffix' => $phys['suffix'],
            'designation' => $phys['designation'],
            'addr_1' => $phys['address_1'],
            'addr_2' => $phys['address_2'],
            'city' => $phys['City'],
            'state' => $phys['State_Province'],
            'zip' => $phys['Zip'],
            'phone' => $phys['Phone'],
            'email' => $phys['Email'],
            'school' => $phys['COLLEGE_CODE'],
            'grad_year' => $phys['YearOfGraduation'],
            'fellow' => $phys['fellows'],
            'prac_pri_code' => $phys['PrimaryPracticeFocusCode'],
            'prac_pri_area' => $phys['PrimaryPracticeFocusArea'],
            'prac_sec_code' => $phys['SecondaryPracticeFocusCode'],
            'prac_sec_area' => $phys['SecondaryPracticeFocusArea'],
            'website' => $phys['website'],
            'aoa_cert' => $phys['AOABoardCertified'],
            'abms_cert' => $phys['ABMS'],
            'lat' => $phys['lat'],
            'lon' => $phys['lon'],
        ];

    }
}
