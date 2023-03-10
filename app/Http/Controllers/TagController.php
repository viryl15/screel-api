<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $allTags = Tag::with('creator')->get();

        return $this->success($allTags);
    }

    public function searchTags($search){
        $per_page = 5;
        if (isset(request()->per_page)){
            $per_page = request()->per_page;
        }

        $result = Tag::where('title', 'like', '%'.$search.'%')->paginate($per_page);

        return $this->success($result, "Found tags!!!");
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
     * @param  \App\Models\Tag  $screelTag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $screelTag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $screelTag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $screelTag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $screelTag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $screelTag)
    {
        //
    }
}
