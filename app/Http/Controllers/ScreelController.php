<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScreelRequest;
use App\Http\Requests\UpdateScreelRequest;
use App\Models\Screel;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ScreelController extends Controller
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


        $user = User::find($validator->validated()['id']);



        return $this->success($user->screels);
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
            'content' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return $this->error('error', Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
        }
        if (auth()->user()->getAuthIdentifier() != $validator->validated()['user_id']){
            return $this->error('error', Response::HTTP_UNPROCESSABLE_ENTITY, ["user_id" => "The user id does not match the connected user."]);
        }

        $screel = Screel::create($validator->validated());


        return $this->success($screel);
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
}
