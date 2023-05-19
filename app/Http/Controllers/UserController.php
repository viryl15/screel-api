<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailNewFollowerJob;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
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
        $connectedScreelerIdentifier = auth()->user()->getAuthIdentifier();
        $allScreelers = User::whereHas('screels')->with(['latestScreel', 'followers'])
                            ->where('_id', '<>', $connectedScreelerIdentifier)
                            ->orderBy('latest_screel.created_at', 'asc')
                            ->paginate($per_page);
        dump($allScreelers);
        dump($allScreelers->isEmpty());
        dd($allScreelers->count());
        if (count($allScreelers['data']) == 0){
            $allScreelers = User::with(['latestScreel', 'followers'])->where('latest_screel.created_at', '>', now()->addMonths(-1))
                ->where('_id', '<>', $connectedScreelerIdentifier)
                ->orderBy('latest_screel.created_at', 'asc')
                ->paginate($per_page);
        }
//        ->where('_id', '<>', $connectedScreelerIdentifier)->with(['latestScreel', 'followers'])
//            ->orderBy('latest_screel.created_at', 'asc')->paginate($per_page);

        return $this->success($allScreelers, "All Screelers.");
    }

    public function followScreeler(Request $request){
        $validator = Validator::make($request->all(), [
            'follower_id' => 'required|exists:users,_id',
            'following_id' => 'required|exists:users,_id',
        ]);
        if ($validator->fails()) {
            return $this->error('error', Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
        }
        $connectedScreelerIdentifier = auth()->user()->getAuthIdentifier();
        $follower_id = $validator->validated()['follower_id'];
        $following_id = $validator->validated()['following_id'];
        if (!$connectedScreelerIdentifier === $follower_id){
            $validator->errors()->add(
                'follower_id', 'Something went wrong.'
            );
            return $this->error('Invalid entries.', Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
        }
        if ($following_id === $follower_id){
            $validator->errors()->add(
                'follower_id', 'You cannot follow yourself.'
            );
            return $this->error('Invalid entries.', Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
        }


        $followingScreeler = User::find($following_id);
        $followerScreeler = User::find($follower_id);

        if ($followingScreeler->followers()->where('_id', $followerScreeler->id)->exists()){
            $validator->errors()->add(
                'follower_id', 'Already followed.'
            );
            return $this->error('Invalid entries.', Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
        }

        $followingScreeler->followers()->syncWithoutDetaching([$followerScreeler->id]);
        $followingScreeler->refresh();

        // send email notification
        $details['email'] = $followingScreeler->email;
        $details['username'] = $followingScreeler->username;
        $details['followerUserName'] = $followerScreeler->username;
        dispatch(new SendEmailNewFollowerJob($details));

        return $this->success(['followings' => $followerScreeler->followings()->count(), 'followers' => $followerScreeler->followers()->count()], "Follower added successfully!");
    }

    public function unfollowScreeler(Request $request){
        $validator = Validator::make($request->all(), [
            'follower_id' => 'required|exists:users,_id',
            'following_id' => 'required|exists:users,_id',
        ]);
        if ($validator->fails()) {
            return $this->error('error', Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
        }
        $connectedScreelerIdentifier = auth()->user()->getAuthIdentifier();
        $follower_id = $validator->validated()['follower_id'];
        $following_id = $validator->validated()['following_id'];

        if (!($connectedScreelerIdentifier === $follower_id)){
            $validator->errors()->add(
                'follower_id', 'Something went wrong. The follower is not the connected screeler.'
            );
            return $this->error('Invalid entries.', Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
        }


        $followingScreeler = User::find($following_id);
        $followerScreeler = User::find($follower_id);

        if (!$followingScreeler->followers()->where('_id', $followerScreeler->id)->exists()){
            $validator->errors()->add(
                'follower_id', "This Screeler's profile is not in your subscription list yet."
            );
            return $this->error('Invalid entries.', Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
        }

        $followingScreeler->followers()->detach($followerScreeler->id);
        $followingScreeler->refresh();

        return $this->success(['followings' => $followerScreeler->followings()->count(), 'followers' => $followerScreeler->followers()->count()], "Successfully unfollowed!");
    }

    public function getFollowers($username){

        if (User::where('username', $username)->exists()) {
            $per_page = 5;
            if (isset(request()->per_page)){
                $per_page = request()->per_page;
            }

            $user = User::where('username', $username)->with('followers', function ($query) use ($per_page){
                $query->paginate($per_page);
            })->firstOrFail();

            return $this->success($user->followers, "Screeler followers.");
        } else {
            return $this->error("User with username " . $username . " doesn't exist.", Response::HTTP_NOT_FOUND);
        }

//        $connectedScreelerIdentifier = auth()->user()->getAuthIdentifier();
//
//        $connectedScreeler = User::findOrFail($connectedScreelerIdentifier);
//
//
//        return $this->success($connectedScreeler->followers, "Screeler followers.");
    }
    public function getFollowings($username){
        if (User::where('username', $username)->exists()) {
            $per_page = 5;
            if (isset(request()->per_page)){
                $per_page = request()->per_page;
            }

            $user = User::where('username', $username)->with('followings', function ($query) use ($per_page){
                $query->paginate($per_page);
            })->firstOrFail();

            return $this->success($user->followings, "Screeler followings.");
        } else {
            return $this->error("User with username " . $username . " doesn't exist.", Response::HTTP_NOT_FOUND);
        }
//        $connectedScreelerIdentifier = auth()->user()->getAuthIdentifier();
//
//        $connectedScreeler = User::findOrFail($connectedScreelerIdentifier);
//
//
//        return $this->success($connectedScreeler->followings, "Screeler followings.");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
