<?php

namespace App\Enums;

enum EstadoEquipoEnum: String
{
    case ESTADO_OPERATIVO = '0';
    case ESTADO_INOPERATIVO = '1';


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
            self::ESTADO_OPERATIVO => 'ESTADO OPERATIVO',
            self::ESTADO_INOPERATIVO => 'ESTADO INOPERATIVO'
        };
    }

    public static function getLabel(?string $value): string
    {
        if ($value === null) {
            return '[NO DISPONIBLE]'; // Retorna un valor por defecto
        }
        return self::tryFrom($value)?->label();
    }
}
