<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stancl\Tenancy\Tenancy;

class TestTenantModel extends Command
{
    protected $signature = 'test:tenant-model';
    protected $description = 'Test if the correct tenant model is being resolved';

    public function handle()
    {
        $tenantModel = config('tenancy.tenant_model');
        $this->info("Configured tenant model: " . $tenantModel);

        // Create a tenant instance and check its class
        $tenant = app($tenantModel);
        $this->info("Created tenant instance class: " . get_class($tenant));

        // Check if the tenant has the database method
        $this->info("Has database method: " . (method_exists($tenant, 'database') ? 'Yes' : 'No'));

        // Check the Tenancy service
        $tenancy = app(Tenancy::class);
        $this->info("Tenancy service tenant model: " . get_class($tenancy->model()));

        return Command::SUCCESS;
    }
}
