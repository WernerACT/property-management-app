<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class LeaseExpiryNotification extends Notification
{
    use Queueable;

    protected $tenantName;
    protected $leaseEndDate;

    public function __construct($tenantName, $leaseEndDate)
    {
        $this->tenantName = $tenantName;
        // Ensure $leaseEndDate is a Carbon instance
        $this->leaseEndDate = Carbon::parse($leaseEndDate);
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Lease Expiry Reminder')
            ->greeting('Hello Admin,')
            ->line("This is a reminder that the lease for tenant **{$this->tenantName}** will expire on **{$this->leaseEndDate->format('F j, Y')}**.")
            ->line('Please ensure to reach out to the tenant to renew or terminate the lease before it expires.')
            ->line('Thank you for your attention.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
