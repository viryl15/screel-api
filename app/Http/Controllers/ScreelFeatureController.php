<?php

namespace App\Http\Controllers;


use App\Models\ScreelFeature;
use App\Models\User;
use App\Traits\ApiResponser;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class ScreelFeatureController extends Controller
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
            'title' => 'required|string',
            'content' => 'required|array',
            'schedule' => 'required',
//            'content.*'     => 'required|json'
        ]);

        if ($validator->fails()) {
            return $this->error('error', Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
        }

        $schedule = $validator->validated()['schedule'];

        $scheduleConvert = new DateTime($schedule);

        if($schedule <= now()->addMinutes(5)){
            $validator->errors()->add(
                'schedule', 'Schedule date must be greater than now.'
            );
            return $this->error('Invalid entries.', Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
        }
        $screeler = User::find(auth()->user()->getAuthIdentifier());
        if ($screeler->username != 'viryl15'){
            return $this->error('error', Response::HTTP_UNAVAILABLE_FOR_LEGAL_REASONS);
        }

//        dd($scheduleConvert);
        $feature = ScreelFeature::create([
            'title' => $validator->validated()['title'],
            'content' => $validator->validated()['content'],
            'schedule' => $validator->validated()['schedule'],
            'created_by' => $screeler->id,
            'sent' => false,
        ]);

        return $this->success($feature, "Feature created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ScreelFeature  $screelFeatures
     * @return \Illuminate\Http\Response
     */
    public function show(ScreelFeature $screelFeatures)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ScreelFeature  $screelFeatures
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ScreelFeature $screelFeatures)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ScreelFeature  $screelFeatures
     * @return \Illuminate\Http\Response
     */
    public function destroy(ScreelFeature $screelFeatures)
    {
        //
    }
}
