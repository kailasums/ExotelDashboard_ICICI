<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\ZoneMaster;
use App\BranchMaster;
use App\RegionMaster;
use App\CallRecording;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CallRecordingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['store']);
    }
    

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
        try {
            $requestDatas = $request->input();
            $requestDatas['created_at'] = Carbon::now();

            if (strpos($request->path(), 'incoming') !== false) {
                $userData = User::where('phone_number', $requestDatas['to_number'])->first();
                $requestDatas['call_directions'] = 'incoming';
            } else {
                $userData = User::where('phone_number', $requestDatas['from_number'])->first();
                $requestDatas['call_directions'] = 'outgoing';
            }

            $requestDatas['group1'] = $userData['group1'];
            $requestDatas['group2'] = $userData['group2'];
            $requestDatas['group3'] = $userData['group3'];
            $requestDatas['group4'] = $userData['group4'];
            $data = CallRecording::create($requestDatas);
            $response['status'] = 200;
            $response['data'] = $data;

            return response()->json($response);
        } catch (\Exception $e) {
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
    public function show(Request $request, CallRecording $callRecording)
    {
        $inputs = $request->all();
        $count = $callRecording->count();
        $start = ($inputs['start']) ? $inputs['start'] : 0;
        $length = ($inputs['length']) ? $inputs['length'] : 10;
        $users = $users = $callRecording->skip($start)->take($length)->get()->toArray();
        $records = [];
        for ($i = 0; $i < count($users); $i++) {
            array_push($records, [$users[$i]['callsid'], $users[$i]['fromNumber'], $users[$i]['toNumber'], $users[$i]['callduration'], $users[$i]['callstatus'], $users[$i]['callRecordingLink'], $users[$i]['callRecordingLink'], $users[$i]['branchId'], $users[$i]['group1'], $users[$i]['group2'], $users[$i]['group3'], $users[$i]['group4'], $users[$i]['created_at']]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CallRecording  $callRecording
     * @return \Illuminate\Http\Response
     */
    public function pieChart(Request $request)
    {
        $user  =  Session::get('user');
        $data = CallRecording::select(
                DB::raw('call_status as callStatus'),
                DB::raw('count(*) as number')
            )
            ->groupBy('call_status')
            ->where('created_at','>=', Carbon::today())
            ->where([ 'group1' => $user->group1])->get();
       
        $array[] = ['Call_Status', 'Number'];
        foreach ($data as $key => $value) {
            $array[++$key] = [$value->callStatus, $value->number];
        }
        $callRecords = json_encode($array);
        $zoneQuery = ZoneMaster::where('mega_zone_id',$user->group1);
        $zones = $zoneQuery->pluck('zone_name', 'id');
        $zoneParam = $zoneQuery->pluck('id')->toArray();
        $regionQuery = RegionMaster::whereIn('zone_id',$zoneParam);
        $regions = $regionQuery->pluck('region_name', 'id');
        $regionParam = $regionQuery->pluck('id')->toArray();
        $branchQuery = BranchMaster::whereIn('region_id',$regionParam);
        $branchs = $branchQuery->pluck('branch_name', 'id');
        $branchParam = $branchQuery->pluck('id')->toArray();
        $pb = User::where(['group1'=>$user->group1,'is_callable'=>'yes'])->pluck('name','id');

        return view('callRecord.google-pie-chart')->with(compact('callRecords', 'zones', 'regions', 'branchs','pb'));
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
