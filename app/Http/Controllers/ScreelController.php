<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScreelRequest;
use App\Http\Requests\UpdateScreelRequest;
use App\Models\Screel;
use App\Models\Tag;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class ScreelController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $per_page = 5;
        if (isset(request()->per_page)){
            $per_page = request()->per_page;
        }
        $allScreels = Screel::with('owner')->latest()->paginate($per_page);

        return $this->success($allScreels, "All feeds.");
    }

    public function validateCurrentUser($id){
        if (auth()->user()->getAuthIdentifier() != $id){
            return $this->error('error', Response::HTTP_UNPROCESSABLE_ENTITY, ["user_id" => "The user id does not match the connected user."]);
        }
    }

    public function getUserScreels($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:users,_id',
        ]);

        if ($validator->fails()) {
            return $this->error('error', Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
        }


        $user = User::findOrFail($validator->validated()['id']);

        $user->load('screels');

        return $this->success($user, $user->name . "'s Screels.");
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreScreelRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,_id',
            'content' => 'required|string|max:255',
            "tags"    => "array|distinct|max:4",
            "tags.*"  => [Rule::requiredIf(isset($request->tags)), "string", "distinct", "max:20",]
//            "tags.*"  => [Rule::requiredIf(isset($request->tags)), "string|max:20",
//                  function ($attribute, $value, $fail) {
//                    if($value != '720DF6C2482218518FA20FDC52D4DED7ECC043AB') {
//                        $fail('Invalid password');
//                    }
//                }],
        ]);

        if ($validator->fails()) {
            return $this->error('error', Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
        }
        if (auth()->user()->getAuthIdentifier() != $validator->validated()['user_id']){
            return $this->error('error', Response::HTTP_UNPROCESSABLE_ENTITY, ["user_id" => "The user id does not match the connected user."]);
        }
        $tagIds = [];
        foreach ($validator->validated()['tags'] as $tagTitle){
            $tag = Tag::whereTitle($tagTitle)->first();
            if (!isset($tag->id)){
                $tag = Tag::create([
                    'title' => $tagTitle,
                    'created_by' => auth()->user()->getAuthIdentifier()
                ]);
            }
            array_push($tagIds, $tag->id);
        }

        $screel = Screel::create([
            'user_id' => $validator->validated()['user_id'],
            'content' => $validator->validated()['content']
        ]);

        $screel->tags()->syncWithoutDetaching($tagIds);


        $screel->refresh();

        $user = User::find(auth()->user()->getAuthIdentifier());
        $user->latestScreel()->associate($screel);
        $user->save();

        try {
            Http::retry(3, 100)->post(env('DISCORD_WEBHOOK_URL'), [
                'content' => "New Screel Alert!",
                'embeds' => [
                    [
                        'title' => "Head to the feed now to check out the latest screel.",
                        'description' => '[' . substr($screel->content, 0, 20) . '...](' . env('FRONT_END_URL') . ')'.' :rocket:',
                        'color' => '7506394',
                    ]
                ],
            ]);
        }catch (\Exception $exception){

        }

        return $this->success(Screel::findOrFail($screel->id), "Stored screel.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Screel  $screel
     * @return \Illuminate\Http\Response
     */
    public function show(Screel $screel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateScreelRequest  $request
     * @param  \App\Models\Screel  $screel
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateScreelRequest $request, Screel $screel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Screel  $screel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Screel $screel)
    {
        //
    }

    public function deleteScreel($id)
    {
        $screel = Screel::findOrFail($id);
        $screel->delete();
        return $this->success([], "Screel deleted.");
    }
}
