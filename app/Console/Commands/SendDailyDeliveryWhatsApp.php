<?php
namespace App\Console\Commands;

use App\Services\WhatsAppService;
use Illuminate\Console\Command;

class SendDailyDeliveryWhatsApp extends Command
{
    protected $signature   = 'whatsapp:daily-delivery {--dry-run : Log only, do not actually send}';
    protected $description = 'Send daily delivery reminder WhatsApp to all active subscription customers';

    public function handle(WhatsAppService $wa): int
    {
        $this->info('Sending daily delivery WhatsApp notifications...');

        if ($this->option('dry-run')) {
            $this->warn('DRY RUN — messages will be logged only.');
        }

        $result = $wa->sendDailyDeliveryNotifications();

        $this->info("✅ Sent: {$result['sent']}");
        $this->warn("❌ Failed: {$result['failed']}");
        $this->line("📊 Total active subscribers: {$result['total']}");

        return self::SUCCESS;
    }
}
