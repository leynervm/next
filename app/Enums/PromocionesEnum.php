<?php

namespace App\Enums;

enum PromocionesEnum: string
{
    case DESCUENTO = '0';
    case COMBO = '1';
    case LIQUIDACION = '2';
    case OFERTA = '3';

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

    public function label(): string
    {
        return match ($this) {
            self::DESCUENTO => 'DESCUENTO',
            self::COMBO => 'COMBO DE PRODUCTOS',
            self::LIQUIDACION => 'LIQUIDACIÓN',
            self::OFERTA => 'OFERTA'
        };
    }

    public function text(): string
    {
        return match ($this) {
            self::DESCUENTO => 'DSCT',
            self::COMBO => 'COMBO',
            self::LIQUIDACION => 'LIQUIDACIÓN',
            self::OFERTA => 'OFERTA'
        };
    }

    public static function getText(?string $value): string
    {
        if ($value === null) {
            return '[PROMOCIÓN]'; // Retorna un valor por defecto
        }
        return self::tryFrom($value)?->text();
    }
}
