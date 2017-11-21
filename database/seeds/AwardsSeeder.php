<?php

use App\Award;
use Illuminate\Database\Seeder;

class AwardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Award::create([
            'name' => 'Spielwarenmesse - Nuremeberg Toy Fair',
            'url'  => 'https://www.spielwarenmesse.de/?L=1',
        ]);
        Award::create([
            'name' => 'Toy Award - Nuremeberg Toy Fair',
            'url'  => 'http://www.toyaward.de/en/2017/',
        ]);
        Award::create([
            'name' => 'Hong Kong Toys & Games Fair',
            'url'  => 'http://m.hktdc.com/fair/hktoyfair-en/HKTDC-Hong-Kong-Toys-and-Games-Fair.html',
        ]);
        Award::create([
            'name' => 'Toy Fair New York',
            'url'  => 'http://www.toyfairny.com/',
        ]);
        Award::create([
            'name' => 'Playtime Paris - Toy Fair Paris',
            'url'  => 'https://www.tradefairdates.com/Playtime-M6553/Paris.html',
        ]);
        Award::create([
            'name' => 'Preshow Noël Jouets & Jeux - Deauville Preshow',
            'url'  => 'http://www.preshow-noel.fr/?lang=en',
        ]);
        Award::create([
            'name' => 'Altea pre-show',
            'url'  => 'http://www.aefj.es/blog/juguetes-pre-show-el-punto-de-encuentro-del-sector',
        ]);
        Award::create([
            'name' => 'Kids Turkey - Istanbul Toy Fair',
            'url'  => 'http://www.kidsturkey.com/en/home',
        ]);
        Award::create([
            'name' => 'Kids Russia Moscow - Moscow Toy Fair',
            'url'  => 'https://www.tradefairdates.com/Kids-Russia-M11890/Moscow.html',
        ]);
        Award::create([
            'name' => 'Kids Time Kielce -  Kielce-Poland Toy Fair',
            'url'  => 'https://www.tradefairdates.com/Kids-Time-M4216/Kielce.html',
        ]);
        Award::create([
            'name' => 'Toy of the Year - Toys Industry Association (TIA), Toy Industy Fundation',
            'url'  => 'http://www.toyawards.org/',
        ]);
        Award::create([
            'name' => 'Mejor Juguete - Asociación Española de Fabricantes de Juguetes (AEFJ)',
            'url'  => 'http://www.mejorjuguete.com/',
        ]);
        Award::create([
            'name' => 'Dr Toy',
            'url'  => 'http://drtoy.com/',
        ]);
        Award::create([
            'name' => 'Best Toys for Kids - American Specialty Toy Retailing Association (Astra) (Educational Toys)',
            'url'  => 'https://www.facebook.com/ASTRABestToys/',
        ]);
        Award::create([
            'name' => "Parent's Choice -  Children's Media & Toys Reviews",
            'url'  => 'http://www.parents-choice.org/award.cfm?thePage=toys&p_code=p_toy',
        ]);
    }
}
