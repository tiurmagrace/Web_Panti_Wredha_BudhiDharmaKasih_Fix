<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;

class CheckBarangNotifications extends Command
{
    protected $signature = 'notifications:check-barang';
    protected $description = 'Cek stok menipis dan barang kadaluarsa, kirim notifikasi ke admin';

    public function handle()
    {
        $this->info('Memeriksa stok barang dan tanggal kadaluarsa...');
        
        $notifications = NotificationService::checkBarangNotifications();
        
        $count = count($notifications);
        
        if ($count > 0) {
            $this->info("✓ {$count} notifikasi baru dibuat.");
        } else {
            $this->info('✓ Tidak ada notifikasi baru.');
        }

        return Command::SUCCESS;
    }
}
