<?php

namespace App\Console\Commands;

use App\Models\Lease;
use App\Notifications\LeaseExpiryNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckLeaseExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lease:check-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for leases expiring within the next 3 months and notify admin.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get admin email from .env
        $adminEmail = env('ADMIN_EMAIL');

        // Get leases expiring in the next 3 months
        $leasesExpiringSoon = Lease::whereBetween('end_date', [Carbon::now(), Carbon::now()->addMonths(3)])
            ->with('tenant')
            ->get();

        // If no expiring leases found, output a message
        if ($leasesExpiringSoon->isEmpty()) {
            $this->info('No leases expiring in the next 3 months.');
            return 0;
        }

        foreach ($leasesExpiringSoon as $lease) {
            // Send notification to the admin for each expiring lease
            Notification::route('mail', $adminEmail)->notify(new LeaseExpiryNotification(
                $lease->tenant->name,
                $lease->end_date
            ));

            // Output info to the console
            $this->info('Notification sent for lease expiring for tenant: ' . $lease->tenant->name . ' on ' . $lease->end_date->format('F j, Y'));
        }

        return 0;
    }
}
