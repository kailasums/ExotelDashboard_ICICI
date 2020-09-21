<?php

namespace App\Http\Controllers;

use App\CallRecording;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CallRecordingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('callRecord.call_records');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $requestDatas = $request->input();
            $requestDatas['created_at'] = Carbon::now();
            $data = CallRecording::create($requestDatas);
            $response['status'] = 200;
            $response['data'] = $data;
            return response()->json($response);
        } catch(\Exception $e) {
            $response['status'] = 500;
            $response['error'] = $e->getMessage();
            return response()->json($response);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CallRecording  $callRecording
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,CallRecording $callRecording)
    {
        $inputs = $request->all();
        $count = $callRecording->count();
        $start = ($inputs['start']) ? $inputs['start'] : 0;
        $length = ($inputs['length']) ? $inputs['length'] : 10;
        $users = $users = $callRecording->skip($start)->take($length)->get()->toArray();
        $records = [];
        for($i=0; $i<count($users); $i++){
            array_push($records, [$users[$i]['callsid'], $users[$i]['fromNumber'],$users[$i]['toNumber'],$users[$i]['callduration'],$users[$i]['callstatus'],$users[$i]['callRecordingLink'],$users[$i]['callRecordingLink'],$users[$i]['branchId'],$users[$i]['group1'],$users[$i]['group2'],$users[$i]['group3'],$users[$i]['group4'],$users[$i]['created_at']]);
        }
        $response = [
        'draw' => 0,
        'recordsTotal' => $count,
        "recordsFiltered" => $count,
        "data" =>
        $records
        ];
        
        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CallRecording  $callRecording
     * @return \Illuminate\Http\Response
     */
    public function edit(CallRecording $callRecording)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CallRecording  $callRecording
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CallRecording $callRecording)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CallRecording  $callRecording
     * @return \Illuminate\Http\Response
     */
    public function destroy(CallRecording $callRecording)
    {
        //
    }
}
