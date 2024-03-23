<?php

namespace Database\Seeders;

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
        DB::select("INSERT INTO subcategories (id, name, orden) 
            VALUES
            (1, 'GAMER', 1),
            (2, 'OFICINA', 2),
            (3, 'HOGAR', 3),
            (4, 'HD', 4),
            (5, '2K', 5),
            (6, '4K', 6),
            (7, 'FULL HD', 7),
            (8, 'METAL', 8),
            (9, 'PLASTICO', 9),
            (10, 'RGB', 10),
            (11, 'INALAMBRICO', 11),
            (12, 'LASER', 12),
            (13, 'TINTA', 13),
            (14, 'DECORACION', 14),
            (16, 'ANTIVIRUS', 16),
            (17, 'NVR', 17),
            (18, 'XVR', 18),
            (19, 'POE', 19),
            (20, 'ANTIVIRUS', 20),
            (21, 'TERMICA', 21),
            (22, 'EMPRESARIAL', 22),
            (23, 'RJ45', 23),
            (24, 'VNC', 24)");
    }
}
