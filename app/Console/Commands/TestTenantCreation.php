<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;

class TestTenantCreation extends Command
{
    protected $signature = 'test:tenant-creation';
    protected $description = 'Test creating a tenant';

    public function handle()
    {
        $this->info('Creating a test tenant...');
        
        try {
            $tenant = Tenant::create([
                'name' => 'Test Tenant',
                'email' => 'test@example.com',
                'password' => 'password123',
                'domain_name' => 'test' . rand(1000, 9999),
            ]);
            
            $domain = $tenant->domains()->create([
                'domain' => $tenant->domain_name . '.' . config('app.domain'),
            ]);
            
            $this->info('Tenant created successfully!');
            $this->info('Tenant ID: ' . $tenant->id);
            $this->info('Domain: ' . $domain->domain);
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to create tenant: ' . $e->getMessage());
            
            return Command::FAILURE;
        }
    }
}
