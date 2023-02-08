<?php

namespace App\Http\Controllers;

use App\Models\Screel;
use App\Models\ScreelReaction;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ScreelReactionController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'screel_id' => 'required|exists:screels,_id',
            'reaction_id' => 'required|exists:reactions,_id',
        ]);

        if ($validator->fails()) {
            return $this->error('error', Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
        }
        try{
            // begin a transaction
            $id = auth()->user()->getAuthIdentifier();
            $screeler = User::findOrFail($id);

            // if the reaction hit by the screeler already exist on that screel
            if (ScreelReaction::where([
                'screel_id' => $validator->validated()['screel_id'],
                'reaction_id' => $validator->validated()['reaction_id'],
            ])->exists()) {
                $screelReaction = ScreelReaction::where([
                    'screel_id' => $validator->validated()['screel_id'],
                    'reaction_id' => $validator->validated()['reaction_id'],
                ])->first();
//            if (User::where('_id', $id)->screelReactions()->where(['screel_id' => $validator->validated()['screel_id']]))
                // if screeler has already react on the same screel whith the same reaction
//            if (ScreelReaction::whereIn('_id',$screeler->screel_reaction_ids)->where('reaction_id',$validator->validated()['reaction_id'])->exists()){
                if (in_array($screeler->id, $screelReaction->screeler_ids)) {
                    //react
                    ScreelReaction::where([
                        'screel_id' => $validator->validated()['screel_id'],
                        'reaction_id' => $validator->validated()['reaction_id'],
                    ])->decrement('count');
                    $screelReaction->screelers()->detach($screeler->id);
                    $screelReaction->refresh();
                } else {
                    //react
                    $screelReaction = ScreelReaction::where([
                        'screel_id' => $validator->validated()['screel_id'],
                        'reaction_id' => $validator->validated()['reaction_id'],
                    ])->increment('count');
                    $screelReaction->screelers()->syncWithoutDetaching([$screeler->id]);
                    $screelReaction->refresh();
                    try {
                        Http::retry(3, 100)->post(env('DISCORD_WEBHOOK_URL'), [
                            'content' => "New Screel Reaction!",
                            'embeds' => [
                                [
                                    'title' => "Head to the feed now to check out the latest screel reactions.",
                                    'description' => '[' . $screelReaction->reaction->label . '...](' . env('FRONT_END_URL') . ')'.' :rocket:',
                                    'color' => '7506394',
                                ]
                            ],
                        ]);
                    }catch (\Exception $exception){

                    }
                }
            } else {
                // create reaction
                $screelReaction = ScreelReaction::create([
                    'screel_id' => $validator->validated()['screel_id'],
                    'reaction_id' => $validator->validated()['reaction_id'],
                    'count' => 1,
                ]);

                $screelReaction->screelers()->syncWithoutDetaching([$screeler->id]);
                $screelReaction->refresh();
                try {
                    Http::retry(3, 100)->post(env('DISCORD_WEBHOOK_URL'), [
                        'content' => "New Screel Reaction!",
                        'embeds' => [
                            [
                                'title' => "Head to the feed now to check out the latest screel reactions.",
                                'description' => '[' . $screelReaction->reaction->label . '...](' . env('FRONT_END_URL') . ')'.' :rocket:',
                                'color' => '7506394',
                            ]
                        ],
                    ]);
                }catch (\Exception $exception){

                }
            }
        }catch (\Exception $exception){
//            dd($exception);
            return $this->error("Something went wrong", Response::HTTP_EXPECTATION_FAILED);
        }

        $screel = Screel::findOrFail($validator->validated()['screel_id']);
        $screel->load(["screelReactions", "owner"]);
        return $this->success($screel, "Screel reactions");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ScreelReaction  $screelReaction
     * @return \Illuminate\Http\Response
     */
    public function show(ScreelReaction $screelReaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ScreelReaction  $screelReaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ScreelReaction $screelReaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ScreelReaction  $screelReaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(ScreelReaction $screelReaction)
    {
        //
    }
}
