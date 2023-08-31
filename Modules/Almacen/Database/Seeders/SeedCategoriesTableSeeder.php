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

        $subcategory1 = Subcategory::create([
            'name' => 'GAMER',
            'code' => Str::random(4),
        ]);
        $subcategory2 = Subcategory::create([
            'name' => 'OFICINA',
            'code' => Str::random(4),
        ]);
        $subcategory3 = Subcategory::create([
            'name' => 'LASER',
            'code' => Str::random(4),
        ]);
        $subcategory4 = Subcategory::create([
            'name' => 'TINTA',
            'code' => Str::random(4),
        ]);
        $subcategory5 = Subcategory::create([
            'name' => 'RGB',
            'code' => Str::random(4),
        ]);


        $pantalla = Category::create([
            'name' => 'PANTALLA',
            'code' => Str::random(4),
        ]);
        $laptops = Category::create([
            'name' => 'LAPTOP',
            'code' => Str::random(4),
        ]);
        $imporesora = Category::create([
            'name' => 'IMPRESORA',
            'code' => Str::random(4),
        ]);
        $desktop = Category::create([
            'name' => 'DESKTOP',
            'code' => Str::random(4),
        ]);
        $mouse = Category::create([
            'name' => 'MOUSE',
            'code' => Str::random(4),
        ]);


        $pantalla->subcategories()->attach([$subcategory1->id, $subcategory2->id]);
        $laptops->subcategories()->attach([$subcategory1->id, $subcategory2->id]);
        $imporesora->subcategories()->attach([$subcategory3->id, $subcategory4->id]);
        $desktop->subcategories()->attach([$subcategory1->id, $subcategory2->id]);
        $mouse->subcategories()->attach([$subcategory1->id, $subcategory2->id, $subcategory5->id]);
    }
}
