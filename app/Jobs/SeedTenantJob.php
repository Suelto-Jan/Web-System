<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Hash;
use App\Models\{
    User,
    Tenant
};

class SeedTenantJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
     protected $tenant;
     public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->tenant->run(function(){
            // Always use the plain text password '12345678' for tenant users
            $plainPassword = '12345678';

            // Log the tenant user creation
            \Log::info('Creating tenant user', [
                'tenant_id' => $this->tenant->id,
                'email' => $this->tenant->email,
                'tenant_name' => $this->tenant->name,
                'password' => $plainPassword
            ]);

            // Create the user with the hashed password
            // We need to hash it manually to ensure it's properly hashed
            $user = User::create([
                'name' => $this->tenant->name,
                'email' => $this->tenant->email,
                'password' => Hash::make($plainPassword),
            ]);

            // Log successful user creation
            \Log::info('Tenant user created successfully', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
        });
    }
}
