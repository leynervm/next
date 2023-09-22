<?php

namespace Modules\Almacen\Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Almacen\Entities\Subcategory;

class SeedCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $gamer = Subcategory::create([
            'name' => 'GAMER',
            'code' => Str::random(4),
        ]);
        $oficina = Subcategory::create([
            'name' => 'OFICINA',
            'code' => Str::random(4),
        ]);
        $laser = Subcategory::create([
            'name' => 'LASER',
            'code' => Str::random(4),
        ]);
        $tinta = Subcategory::create([
            'name' => 'TINTA',
            'code' => Str::random(4),
        ]);
        $rgb = Subcategory::create([
            'name' => 'RGB',
            'code' => Str::random(4),
        ]);
        $ptdos = Subcategory::create([
            'name' => 'PT2',
            'code' => Str::random(4),
        ]);
        $tubo = Subcategory::create([
            'name' => 'TUBO',
            'code' => Str::random(4),
        ]);
        $trescientossesenta = Subcategory::create([
            'name' => '360°',
            'code' => Str::random(4),
        ]);
        $inalambrico = Subcategory::create([
            'name' => 'INALAMBRICO',
            'code' => Str::random(4),
        ]);
        $audifonos = Subcategory::create([
            'name' => 'AUDÍFONOS',
            'code' => Str::random(4),
        ]);
        $padmouse = Subcategory::create([
            'name' => 'PAD MOUSE',
            'code' => Str::random(4),
        ]);
        $cooler = Subcategory::create([
            'name' => 'COOLER',
            'code' => Str::random(4),
        ]);
        $bluetooh = Subcategory::create([
            'name' => 'BLUETOOH',
            'code' => Str::random(4),
        ]);
        $wifi = Subcategory::create([
            'name' => 'WI-FI',
            'code' => Str::random(4),
        ]);
        $numerico = Subcategory::create([
            'name' => 'NUMERICO',
            'code' => Str::random(4),
        ]);
        $cuatrok = Subcategory::create([
            'name' => '4K',
            'code' => Str::random(4),
        ]);
        $lcd = Subcategory::create([
            'name' => 'LCD',
            'code' => Str::random(4),
        ]);
        $ips = Subcategory::create([
            'name' => 'IPS',
            'code' => Str::random(4),
        ]);






        $pantalla = Category::create([
            'name' => 'MONITORES',
            'code' => Str::random(4),
        ]);
        $laptops = Category::create([
            'name' => 'LAPTOPs',
            'code' => Str::random(4),
        ]);
        $impresora = Category::create([
            'name' => 'IMPRESORAs',
            'code' => Str::random(4),
        ]);
        $desktop = Category::create([
            'name' => "PC's ESCRITORIO",
            'code' => Str::random(4),
        ]);
        $mouse = Category::create([
            'name' => 'MOUSES',
            'code' => Str::random(4),
        ]);
        $camaras = Category::create([
            'name' => 'CÁMARAS SEGURIDAD',
            'code' => Str::random(4),
        ]);
        $sensores = Category::create([
            'name' => 'SENSORES',
            'code' => Str::random(4),
        ]);
        $accesorios = Category::create([
            'name' => 'ACCESORIOS',
            'code' => Str::random(4),
        ]);
        $conectividad = Category::create([
            'name' => 'CONECTIVIDAD',
            'code' => Str::random(4),
        ]);
        $teclados = Category::create([
            'name' => 'TECLADOS',
            'code' => Str::random(4),
        ]);


        $pantalla->subcategories()->attach([$gamer->id, $oficina->id, $lcd->id, $ips->id, $cuatrok->id]);
        $laptops->subcategories()->attach([$gamer->id, $oficina->id, $numerico->id]);
        $impresora->subcategories()->attach([$laser->id, $tinta->id]);
        $desktop->subcategories()->attach([$gamer->id, $oficina->id]);
        $mouse->subcategories()->attach([$gamer->id, $oficina->id, $rgb->id]);
        $camaras->subcategories()->attach([$cuatrok->id, $trescientossesenta->id, $ptdos->id, $tubo->id, $inalambrico->id, $bluetooh->id]);
        $sensores->subcategories()->attach([$inalambrico->id, $bluetooh->id, $wifi->id]);
        $accesorios->subcategories()->attach([$audifonos->id, $padmouse->id, $cooler->id]);
        $conectividad->subcategories()->attach([$wifi->id, $bluetooh->id]);
        $teclados->subcategories()->attach([$gamer->id, $oficina->id, $rgb->id, $numerico->id]);
    }
}
