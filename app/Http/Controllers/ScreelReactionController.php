<?php

namespace App\Http\Controllers;

use App\Models\ScreelReaction;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use function League\Flysystem\path;

class ScreelReactionController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $url = Storage::url('Fire.webp');

        return $this->success([env('APP_URL') . $url]);
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
