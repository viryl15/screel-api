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
//        $viryl = User::where('username', 'viryl15')->first();
//        $feature = ScreelFeature::create([
//            'title' => 'Follow',
//            'content' => 'Follow content',
//            'schedule' => Carbon::now()->addHour()->toDate(),
//            'created_by' => $viryl->id,
//            'sent' => false,
//        ]);
        //start
        $feature = ScreelFeature::findOrFail('63d55ce17b160c0dc40954a2');
//
//        $screelersAlreadyReceive = User::whereNotNull('feature_id')->get();
//        foreach ($screelersAlreadyReceive as $item) {
//            $item->userScreelFeatures()->sync(['63d55ce17b160c0dc40954a2']);
//        }
        //end
//        $admin = User::doesntHave('userScreelFeatures', function ($query) use ($feature){
//                            $query->where('_id', $feature->id);
//                        }
//                    )->where('username', 'viryl15')->first();
        $screelers = User::whereNull('feature_id')->get();
//        $screelers = User::doesntHave('userScreelFeatures', function ($query) use ($feature){
//                            $query->where('_id', '63d561979b28ef03a70ca153')
//                                ->orWhere('_id', '63d55d205edb60dd39045392')
//                                ->orWhere('_id', '63d55d5dff4fc7a5fc0b1492');
//                        })->get();
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
