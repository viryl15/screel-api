<?php

namespace App\Jobs;

use App\Mail\NewFeatures;
use App\Models\ScreelFeature;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailNewFeaturesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $viryl = User::where('username', 'viryl15')->first();
        $feature = ScreelFeature::create([
            'title' => 'Follow',
            'content' => 'Follow content',
            'schedule' => Carbon::now()->addHour()->toDate(),
            'created_by' => $viryl->id,
            'sent' => false,
        ]);

//        $admin = User::doesntHave('userScreelFeatures', function ($query) use ($feature){
//                            $query->where('_id', $feature->id);
//                        }
//                    )->where('username', 'viryl15')->first();

        $screelers = User::doesntHave('userScreelFeatures', function ($query) use ($feature){
                            $query->where('_id', $feature->id);
                        })->get();
        foreach ($screelers as $screeler){
            try {
                $email = new NewFeatures($screeler->username);
                \Mail::to($screeler->email)->send($email);
                $screeler->userScreelFeatures()->syncWithoutDetaching([$feature->id]);
            }catch (\Exception $exception){

            }
        }
    }
}
