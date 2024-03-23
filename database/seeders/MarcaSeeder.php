<?php

namespace Database\Seeders;

use App\Models\Marca;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::select("INSERT INTO marcas (id, name) 
            VALUES
            (1, 'LENOVO'),
            (2, 'ASUS'),
            (3, 'HP'),
            (4, 'BROTHER'),
            (5, 'DELL'),
            (6, 'SAMSUNG'),
            (7, 'VIEWSONYC'),
            (8, 'REGRADON'),
            (9, 'HALION'),
            (10, 'CORSAIR'),
            (11, 'AMD'),
            (12, 'DAHUA'),
            (13, 'HIKVISION'),
            (14, 'LG'),
            (15, 'HUAWEI'),
            (16, 'NEXXT'),
            (17, 'KINGSTONE'),
            (18, 'JBL'),
            (19, 'T-DAGGER'),
            (20, 'HIKVISION'),
            (21, 'HYSENCE')");
    }
}
