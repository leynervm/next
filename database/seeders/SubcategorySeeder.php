<?php

namespace Database\Seeders;

use App\Models\Subcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Subcategory::firstOrCreate(['name' => 'GAMER', 'orden' => 1]);
        Subcategory::firstOrCreate(['name' => 'OFICINA', 'orden' => 2]);
        Subcategory::firstOrCreate(['name' => 'HOGAR', 'orden' => 3]);
        Subcategory::firstOrCreate(['name' => 'HD', 'orden' => 4]);
        Subcategory::firstOrCreate(['name' => '2K', 'orden' => 5]);
        Subcategory::firstOrCreate(['name' => '4K', 'orden' => 6]);
        Subcategory::firstOrCreate(['name' => 'FULL HD', 'orden' => 7]);
        Subcategory::firstOrCreate(['name' => 'METAL', 'orden' => 8]);
        Subcategory::firstOrCreate(['name' => 'PLASTICO', 'orden' => 9]);
        Subcategory::firstOrCreate(['name' => 'RGB', 'orden' => 10]);
        Subcategory::firstOrCreate(['name' => 'INALAMBRICO', 'orden' => 11]);
        Subcategory::firstOrCreate(['name' => 'LASER', 'orden' => 12]);
        Subcategory::firstOrCreate(['name' => 'TINTA', 'orden' => 13]);
        Subcategory::firstOrCreate(['name' => 'DECORACION', 'orden' => 14]);
        Subcategory::firstOrCreate(['name' => 'ANTIVIRUS', 'orden' => 15]);
        Subcategory::firstOrCreate(['name' => 'NVR', 'orden' => 16]);
        Subcategory::firstOrCreate(['name' => 'XVR', 'orden' => 17]);
        Subcategory::firstOrCreate(['name' => 'POE', 'orden' => 18]);
        Subcategory::firstOrCreate(['name' => 'ANTIVIRUS', 'orden' => 19]);
        Subcategory::firstOrCreate(['name' => 'TERMICA', 'orden' => 20]);
        Subcategory::firstOrCreate(['name' => 'EMPRESARIAL', 'orden' => 21]);
        Subcategory::firstOrCreate(['name' => 'RJ45', 'orden' => 22]);
        Subcategory::firstOrCreate(['name' => 'VNC', 'orden' => 23]);

        // DB::select("INSERT INTO subcategories (id, name, orden) 
        //     VALUES
        //     (1, 'GAMER', 1),
        //     (2, 'OFICINA', 2),
        //     (3, 'HOGAR', 3),
        //     (4, 'HD', 4),
        //     (5, '2K', 5),
        //     (6, '4K', 6),
        //     (7, 'FULL HD', 7),
        //     (8, 'METAL', 8),
        //     (9, 'PLASTICO', 9),
        //     (10, 'RGB', 10),
        //     (11, 'INALAMBRICO', 11),
        //     (12, 'LASER', 12),
        //     (13, 'TINTA', 13),
        //     (14, 'DECORACION', 14),
        //     (15, 'ANTIVIRUS', 16),
        //     (16, 'NVR', 17),
        //     (17, 'XVR', 18),
        //     (18, 'POE', 19),
        //     (29, 'ANTIVIRUS', 20),
        //     (20, 'TERMICA', 21),
        //     (21, 'EMPRESARIAL', 22),
        //     (22, 'RJ45', 23),
        //     (23, 'VNC', 24)");
    }
}
