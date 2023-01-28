<?php

namespace App\Console\Commands;

use App\Jobs\SendEmailNewFeaturesJob;
use App\Models\ScreelFeature;
use Illuminate\Console\Command;

class PublishScreelFeatures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'screel:publish:features';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        dispatch(new SendEmailNewFeaturesJob());
        return Command::SUCCESS;
    }
}
