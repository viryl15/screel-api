<?php

namespace App\Console\Commands;

use App\Models\Screel;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class RemoveMoreThan24hScreels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'screels:prune-24h';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune all screels created 24 hours ago.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date24 = Carbon::now()->subDay();
        Screel::where('created_at', '<=', $date24)->delete();
//        $screelIds = array_map(fn($screel) => $screel['_id'], $screelsToPrune);

        $this->info('Screels prune successfully!');
        return Command::SUCCESS;
    }
}
