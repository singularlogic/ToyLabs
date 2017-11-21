<?php

use App\Certification;
use Illuminate\Database\Seeder;

class CertificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Certification::create(['name' => 'ISO9001 - Quality Management Systems']);
        Certification::create(['name' => 'ISO14001 - Environmental Management Systems']);
        Certification::create(['name' => 'ISO14006 - Eco-design Management']);
        Certification::create(['name' => 'EMAS Verification']);
        Certification::create(['name' => 'ICTI - International Council of Toy Industries']);
        Certification::create(['name' => 'CSR - Corporate Social Responsibility']);
        Certification::create(['name' => 'SMETA - Sedex Members Ethical Trade Audit']);
        Certification::create(['name' => 'SA800 - Certification of Social Accountability']);
    }
}
