<?php

namespace App\Enums;

enum FilterReportsEnum: string
{
        // case HOY = '0';
        // case AYER = '1';
    case DEFAULT = '0';
    case DIARIO = '1';
    case SEMANA_ACTUAL = '2';
    case MES_ACTUAL = '3';
    case ANIO_ACTUAL = '4';
    case RANGO_DIAS = '6';
    case SEMANAL = '7';
    case MENSUAL = '8';
    case RANGO_MESES = '9';
    case ANUAL = '10';
    case ULTIMA_SEMANA = '11';
    case ULTIMO_MES = '12';
    case ULTIMO_ANIO = '13';
    case DIAS_SELECCIONADOS = '14';
    case MESES_SELECCIONADOS = '15';

        // FOR VENTAS
    case TOP_TEN_VENTAS = '16';
    case VENTAS_POR_COBRAR = '17';

        // FOR PRODUCTOS
    case TOP_TEN_PRODUCTOS = '18';
    case KARDEX_PRODUCTOS = '19';
    case MIN_STOCK = '20';
    case PRODUCTOS_PROMOCIONADOS = '21';
    case CATALOGO_PRODUCTOS = '22';

    case CONSOLIDADO = '100';

    public static function values(): array
    {
        return array_map(fn($enum) => $enum->value, self::cases());
    }

    public static function all(): array
    {
        return array_map(fn($enum) => [
            'value' => $enum->value,
            'label' => $enum->label(),
        ], self::cases());
    }

    // public static function filterEmployers(): array
    // {

    //     $values = [
    //         Self::DEFAULT->value,
    //         Self::MES_ACTUAL->value,
    //         Self::ANIO_ACTUAL->value,
    //         Self::MENSUAL->value,
    //         Self::RANGO_MESES->value,
    //         Self::ANUAL->value,
    //         Self::ULTIMO_MES->value,
    //         Self::ULTIMO_ANIO->value,
    //         Self::MESES_SELECCIONADOS->value,
    //     ];

    //     return array_values(array_map(
    //         fn($enum) => [
    //             'value' => $enum->value,
    //             'label' => $enum->label(),
    //         ],
    //         array_filter(
    //             self::cases(),
    //             fn($enum) => in_array($enum->value, $values)
    //         )
    //     ));
    // }

    public static function except($except = []): array
    {
        return array_values(array_map(
            fn($enum) => [
                'value' => $enum->value,
                'label' => $enum->label(),
            ],
            array_filter(self::cases(), fn($enum) => !in_array($enum->value, $except))
        ));
    }

    public static function in($includes = []): array
    {
        return array_values(array_map(
            fn($enum) => [
                'value' => $enum->value,
                'label' => $enum->label(),
            ],
            array_filter(self::cases(), fn($enum) => in_array($enum->value, $includes))
        ));
    }

    public function label(): string
    {
        return match ($this) {
            self::DEFAULT => 'GENERAL',
            // self::AYER => 'AYER',
            self::DIARIO => 'DIARIO',
            self::SEMANA_ACTUAL => 'SEMANA ACTUAL',
            self::MES_ACTUAL => 'MES ACTUAL',
            self::ANIO_ACTUAL => 'AÑO ACTUAL',
            self::RANGO_DIAS => 'RANGO DE FECHAS',
            self::SEMANAL => 'SEMANAL',
            self::MENSUAL => 'MENSUAL',
            self::RANGO_MESES => 'RANGO DE MESES',
            self::ANUAL => 'ANUAL',
            self::ULTIMA_SEMANA => 'ÚLTIMA SEMANA',
            self::ULTIMO_MES => 'ÚLTIMO MES',
            self::ULTIMO_ANIO => 'ÚLTIMO AÑO',
            self::DIAS_SELECCIONADOS => 'DIAS SELECCIONADOS',
            self::MESES_SELECCIONADOS => 'MESES SELECCIONADOS',
            self::TOP_TEN_VENTAS => 'TOP 10 DE VENTAS',
            self::VENTAS_POR_COBRAR => 'VENTAS POR COBRAR',
            self::TOP_TEN_PRODUCTOS => 'TOP 10 DE PRODUCTOS MÁS VENDIDOS',
            self::KARDEX_PRODUCTOS => 'KARDEX DEL PRODUCTO',
            self::MIN_STOCK => 'PRÓXIMOS A AGOTARSE',
            self::PRODUCTOS_PROMOCIONADOS => 'PRODUCTOS EN PROMOCIÓN',
            self::CATALOGO_PRODUCTOS => 'CATÁLOGO DE PRODUCTOS',
            self::CONSOLIDADO => 'CONSOLIDADO DE INFORMACIÓN',
        };
    }

    public static function getValue(?string $name): ?string
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case->value;
            }
        }

        return null;
    }

    public static function getLabel(?string $value): string
    {
        if ($value === null) {
            return '[TIPO DE FILTRO NO DISPONIBLE]'; // Retorna un valor por defecto
        }
        return self::tryFrom($value)?->label();
    }
}
