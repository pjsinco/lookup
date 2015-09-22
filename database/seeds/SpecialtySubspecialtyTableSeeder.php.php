<?php

use Illuminate\Database\Seeder;

class SpecialtySubspecialtyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('specialty_subspecialty')->truncate();
        $specialties = $this->getCsv('mapping.csv');
        $this->seedTable($specialties);
    }

    /**
     * Seed the specialties table.
     *
     * @return void
     * @author PJ
     */
    private function seedTable($data)
    {
        foreach ($data as $lineIndex => $row) {
            // TODO we don't want to create a Specialty
            $specialty = App\Specialty::create([
                'specialty_id' => $row[0],
                'subspecialty_id' => $row[1],
            ]);
        }
    }

    /**
     * Get CSV file and turn it into a multidimensional array.
     * File needs to be in /database/seeds/data directory.
     *
     * @return array
     * @author PJ
     */
    private function getCsv($filename)
    {
        $csv = \League\Csv\Reader::createFromPath(
            database_path() . '/seeds/data/' . $filename
        );

        return $csv->query();
    }
}
