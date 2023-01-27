<?php

namespace App\Jobs;

use App\Mail\NewFeatures;
use App\Models\ScreelFeature;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailNewFeaturesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $feature;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ScreelFeature $feature)
    {
        $this->feature = $feature;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new NewFeatures($this->feature, 'viryl15');
        \Mail::to('v15.viryl15@gmail.com')->send($email);
//        $screelers = User::all();
//        foreach ($screelers as $screeler){
//            \Mail::to($screeler->email)->send($email);
//        }
    }
}
