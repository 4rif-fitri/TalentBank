<?php

namespace Database\Seeders;

use App\Models\IndustrySector;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IndustrySectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sectors = [
            'Agriculture, Forestry, and Fishing',
            'Mining and Quarrying',
            'Manufacturing',
            'Electricity, Gas, Steam, and Air Conditioning Supply',
            'Water Supply; Sewerage, Waste Management, and Remediation Activities',
            'Construction',
            'Wholesale and Retail Trade; Repair of Motor Vehicles and Motorcycles',
            'Transportation and Storage',
            'Accommodation and Food Service Activities',
            'Information and Communication',
            'Financial and Insurance Activities',
            'Real Estate Activities',
            'Professional, Scientific, and Technical Activities',
            'Administrative and Support Service Activities',
            'Public Administration and Defense; Compulsory Social Security',
            'Education',
            'Human Health and Social Work Activities',
            'Arts, Entertainment, and Recreation',
            'Other Service Activities',
            'Activities of Households as Employers; Undifferentiated Goods- and Services-Producing Activities of Households for Own Use',
            'Activities of Extraterritorial Organizations and Bodies',
        ];

        foreach ($sectors as $sector) {
            IndustrySector::create(['name' => $sector]);
        }
    }
}
