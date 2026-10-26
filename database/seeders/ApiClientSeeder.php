<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ApiClient;
use Illuminate\Database\Seeder;

class ApiClientSeeder extends Seeder
{
    public function run(): void
    {
        $client = ApiClient::firstOrCreate(
            ['name' => 'Client Vania Integration'],
            [
                'api_key' => bin2hex(random_bytes(32)),
                'is_active' => true,
            ]
        );

        $this->command->info('API Key untuk "'.$client->name.'": '.$client->api_key);
    }
}
