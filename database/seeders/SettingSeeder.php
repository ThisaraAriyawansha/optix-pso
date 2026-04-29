<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'shop_name'              => 'OptiX Computer Shop',
            'shop_phone'             => '011-2345678',
            'shop_email'             => 'info@optix.lk',
            'shop_address'           => 'No. 123, Main Street, Colombo 03',
            'invoice_footer'         => 'Thank you for your business! All sales are final. Warranty claims within 7 days with original receipt.',
            'currency_symbol'        => 'Rs.',
            'tax_rate'               => '0',
            'default_payment_method' => 'cash',
            'loyalty_enabled'        => '1',
            'loyalty_points_per_unit' => '100',
            'loyalty_point_value'    => '1',
            'repair_sla_days'        => '3',
        ];

        foreach ($defaults as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
