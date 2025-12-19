<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ProductionStageUpdated;
use App\Services\WhatsAppService;


class SendWhatsAppNotification implements ShouldQueue
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function handle(ProductionStageUpdated $event)
    {
        // Kirim notifikasi WhatsApp
        $result = $this->whatsappService->sendProductionUpdate(
            $event->order,
            $event->stage,
            $event->additionalInfo
        );

        // Update timeline dengan info notifikasi
        $event->order->productionTimelines()->create([
            'stage' => $event->stage,
            'notes' => 'Notifikasi WhatsApp terkirim',
            'notified_at' => now()
        ]);
    }
}