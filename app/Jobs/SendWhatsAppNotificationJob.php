<?php

namespace App\Jobs;

use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendWhatsAppNotificationJob implements ShouldQueue
{
    use Queueable;
    
    public $tries = 3;
    public $backoff = [60, 180, 300];
    
    protected $order;
    protected $stage;
    protected $additionalInfo;
    
    public function __construct($order, $stage, $additionalInfo = '')
    {
        $this->order = $order;
        $this->stage = $stage;
        $this->additionalInfo = $additionalInfo;
    }
    
    public function handle(WhatsAppService $whatsapp)
    {
        $whatsapp->sendProductionUpdate(
            $this->order,
            $this->stage,
            $this->additionalInfo
        );
    }
    
    public function failed(\Throwable $exception)
    {
        Log::error('WhatsApp Job Failed: ' . $exception->getMessage());
    }
}