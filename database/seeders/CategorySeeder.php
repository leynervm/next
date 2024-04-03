<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Category::firstOrCreate(['name' => 'LAPTOPS', 'orden' => 1]);
        Category::firstOrCreate(['name' => 'IMPRESORAS', 'orden' => 2]);
        Category::firstOrCreate(['name' => 'MONITORES', 'orden' => 3]);
        Category::firstOrCreate(['name' => 'PARLANTES', 'orden' => 4]);
        Category::firstOrCreate(['name' => 'AUDIFONOS', 'orden' => 5]);
        Category::firstOrCreate(['name' => 'MOUSE', 'orden' => 6]);
        Category::firstOrCreate(['name' => 'ACCESORIOS', 'orden' => 7]);
        Category::firstOrCreate(['name' => 'CAMARAS DE SEGURIDAD', 'orden' => 8]);
        Category::firstOrCreate(['name' => 'SENSORES', 'orden' => 9]);
        Category::firstOrCreate(['name' => 'ROUTERS', 'orden' => 10]);
        Category::firstOrCreate(['name' => 'TECLADOS', 'orden' => 11]);
        Category::firstOrCreate(['name' => 'SOFTWARE', 'orden' => 12]);
        Category::firstOrCreate(['name' => 'SERVIDORES', 'orden' => 13]);
        Category::firstOrCreate(['name' => 'GRAVADORES', 'orden' => 14]);
        Category::firstOrCreate(['name' => 'SWITCH', 'orden' => 15]);
        Category::firstOrCreate(['name' => 'ANTENAS', 'orden' => 16]);
        Category::firstOrCreate(['name' => 'CASES', 'orden' => 17]);
        Category::firstOrCreate(['name' => 'CONECTORES', 'orden' => 18]);
        Category::firstOrCreate(['name' => 'ESTABILIZADORES', 'orden' => 19]);
        Category::firstOrCreate(['name' => 'AIRE ACONDICIONADO', 'orden' => 20]);

        DB::select("INSERT INTO category_subcategory (id, category_id, subcategory_id) 
            VALUES
            (1, 1, 1),
            (2, 1, 2),
            (3, 2, 2),
            (4, 2, 12),
            (5, 2, 13),
            (6, 2, 11),
            (7, 2, 16),
            (8, 3, 1),
            (9, 3, 2),
            (10, 3, 3),
            (11, 3, 4),
            (12, 3, 5),
            (13, 3, 6),
            (14, 3, 7),
            (15, 4, 2),
            (16, 4, 3),
            (17, 4, 11),
            (18, 5, 1),
            (19, 5, 10),
            (20, 5, 11),
            (21, 6, 1),
            (22, 6, 2),
            (23, 6, 10),
            (24, 6, 11),
            (25, 7, 14),
            (26, 8, 2),
            (27, 8, 3),
            (28, 8, 4),
            (29, 8, 5),
            (30, 8, 6),
            (31, 8, 7),
            (32, 8, 8),
            (33, 8, 9),
            (34, 8, 11),
            (35, 9, 2),
            (36, 9, 3),
            (37, 10, 2),
            (38, 10, 3),
            (39, 11, 1),
            (40, 11, 3),
            (41, 11, 9),
            (42, 11, 10),
            (43, 11, 11),
            (44, 12, 20),
            (45, 13, 22),
            (46, 14, 4),
            (47, 14, 5),
            (48, 14, 6),
            (49, 14, 7),
            (50, 14, 17),
            (51, 14, 18),
            (52, 14, 19),
            (53, 14, 22),
            (54, 15, 3),
            (55, 15, 19),
            (56, 15, 22),
            (57, 16, 11),
            (58, 17, 1),
            (59, 17, 8),
            (60, 17, 9),
            (61, 17, 22),
            (62, 18, 23),
            (63, 18, 23),
            (64, 19, 2),
            (65, 19, 22),
            (66, 20, 2),
            (67, 20, 3),
            (68, 20, 22)");
    }
}
