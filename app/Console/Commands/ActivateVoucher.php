<?php

namespace App\Console\Commands;

use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ActivateVoucher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vouchers:activate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate vouchers that have reached their activation time and have not expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now(new \DateTimeZone('Asia/Jakarta'));
        $vouchers = Voucher::where('is_active', false)
            ->where('activation_time', '<=', $now)
            ->where('expiration_time', '>', $now)
            ->get();

        if (!$vouchers) {
            $this->info('No vouchers to activate');
            return 0;
        }

        foreach ($vouchers as $voucher) {
            $voucher->is_active = true;
            $voucher->save();
            $this->info('Voucher with code ' . $voucher->code . ' has been activated.');
        }

        return 0;
    }
}
