<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Cashier = 'cashier';
    case Technician = 'technician';

    public function label(): string
    {
        return match($this) {
            self::Admin => 'Admin',
            self::Cashier => 'Cashier',
            self::Technician => 'Technician',
        };
    }
}
