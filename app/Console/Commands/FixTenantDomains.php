<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stancl\Tenancy\Database\Models\Domain;

class FixTenantDomains extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:fix-domains';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix tenant domains by removing port numbers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing tenant domains by removing port numbers...');
        
        $domains = Domain::all();
        $fixedCount = 0;
        
        foreach ($domains as $domain) {
            // Check if domain contains a port number
            if (preg_match('/:\d+$/', $domain->domain)) {
                $oldDomain = $domain->domain;
                
                // Remove port number
                $newDomain = preg_replace('/:\d+$/', '', $domain->domain);
                
                // Update the domain
                $domain->domain = $newDomain;
                $domain->save();
                
                $this->info("Fixed domain: {$oldDomain} → {$newDomain}");
                $fixedCount++;
            }
        }
        
        if ($fixedCount > 0) {
            $this->info("Successfully fixed {$fixedCount} domains.");
        } else {
            $this->info('No domains needed fixing.');
        }
        
        return Command::SUCCESS;
    }
}
