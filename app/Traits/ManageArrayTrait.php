<?php

namespace App\Traits;

use App\Models\Ubigeo;
use Illuminate\Support\Facades\DB;

trait ManageArrayTrait
{

    protected function existsInArrayByKey(array $array, string $key, $value, $toLowercase = true): bool
    {
        $coincidencias = array_filter($array, function ($item) use ($key, $value, $toLowercase) {
            $valueItem = $toLowercase ? toStrLowercase($item[$key]) : $item[$key];
            $valueCompare = $toLowercase ? toStrLowercase($value) : $value;

            return ($valueItem == $valueCompare);
        });
        return (count($coincidencias) > 0);
    }

    protected function addToArray($data, array $array = []): array
    {
        $array[] = $data;
        return array_values($array);
    }

    protected function removeFromArrayByKey(array $array, string $key, $value): array
    {
        $newArray = array_filter($array, function ($item) use ($key, $value) {
            return !(toStrLowercase($item[$key]) == toStrLowercase($value));
        });
        return  array_values($newArray);
    }

    protected function findInArrayByKey(array $array, string $key, $value, $toLowercase = true): array | null
    {
        $coincidencias = array_values(array_filter($array, function ($item) use ($key, $value, $toLowercase) {
            $valueItem = $toLowercase ? toStrLowercase($item[$key]) : $item[$key];
            $valueCompare = $toLowercase ? toStrLowercase($value) : $value;
            return $valueItem == $valueCompare;
        }));

        return count($coincidencias) > 0 ? $coincidencias[0] : null;
    }


    protected function getFilterArrayEstablecimientos($establecimientos = [])
    {
        $filters = array_map(function ($local) {
            if (array_key_exists('ubigeo', $local)) {
                return ['ubigeo' => $local['ubigeo']];
            } elseif (array_key_exists('distrito', $local)) {
                return [
                    'distrito_provincia' => $local['distrito'] . "_" . $local['provincia'],
                    // 'provincia' => $local['provincia']
                ];
            }
        }, $establecimientos);

        $keys = array_unique(array_map(function ($item) {
            return key($item);
        }, $filters));
        $values = array_unique(array_map(function ($item) {
            return current($item);
        }, $filters));
        return [current($keys) => array_values($values)];
    }

    protected function mapToArrayEstablecimientos($establecimientos = [], $searchUbigeo = false)
    {
        $ubigeos = [];
        $locales = [];
        $dinamicCode = "XXXXX0";
        if (count($establecimientos) > 0) {
            $filters = Self::getFilterArrayEstablecimientos($establecimientos);
            if (count($filters) > 0 && $searchUbigeo) {
                $ubigeos = Ubigeo::query()->select('id', 'ubigeo_inei', 'distrito', 'provincia', 'region')
                    ->when(array_key_exists('distrito_provincia', $filters), function ($q) use ($filters) {
                        $placeholders = implode(',', array_fill(0, count(current($filters)), '?'));
                        $q->whereRaw("CONCAT(distrito, '_', provincia) In ($placeholders)", array_map('strval', current($filters)));
                    })->when(array_key_exists('ubigeo', $filters), function ($q) use ($filters) {
                        $q->whereIn("ubigeo_inei", array_map('strval', current($filters)));
                    })->get()->toArray();
            }
            $locales = array_map(function ($local) use ($ubigeos, &$dinamicCode) {
                $dinamicCode++;
                // dd(substr($dinamicCode, -4));
                $ubigeo = array_values(array_filter($ubigeos, function ($item) use ($local) {
                    return $item['ubigeo_inei'] == $local['ubigeo'];
                })) ?? [];

                return [
                    'direccion' => array_key_exists('direccion', $local) ? $local['direccion'] : '',
                    'departamento' => count($ubigeo) > 0 ? $ubigeo[0]['region'] : $local['departamento'] ?? '',
                    'provincia' => count($ubigeo) > 0 ? $ubigeo[0]['provincia'] : $local['provincia'] ?? '',
                    'distrito' => count($ubigeo) > 0 ? $ubigeo[0]['distrito'] : $local['distrito'] ?? '',
                    'ubigeo' => count($ubigeo) > 0 ? $ubigeo[0]['ubigeo_inei'] : $local['ubigeo'] ?? '',
                    'ubigeo_id' => count($ubigeo) > 0 ? $ubigeo[0]['id'] : null,
                    'codigo' => array_key_exists('codigo', $local) ? $local['codigo'] : substr($dinamicCode, -4),
                ];
            }, $establecimientos);

            return array_values($locales);
        }

        return $locales;
    }
}
