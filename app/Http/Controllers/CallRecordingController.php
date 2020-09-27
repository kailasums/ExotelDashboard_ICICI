<?php

namespace App\Http\Controllers;

use App\User;
use stdClass;
use Exception;
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
			dd($userData);
			if (!$userData) {
				$response['status'] = 422;
				$response['error'] = "No Records match with phone number";
				return response()->json($response);
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
			array_push($records, [$users[$i]['id'], $users[$i]['from_number'], $users[$i]['to_number'], $users[$i]['call_duration'], $users[$i]['call_status'], $users[$i]['call_recording_link'], $users[$i]['group1'], $users[$i]['group2'], $users[$i]['group3'], $users[$i]['group4'], $users[$i]['created_at']]);
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
			$queryParam = $request->all();
			$user  =  Session::get('user');
			$dataQuery = CallRecording::group($user->group4)
				->select(
					DB::raw('call_status as callStatus'),
					DB::raw('count(*) as number')
				)
				->groupBy('call_status')
				->where('created_at', '>=', Carbon::today());

			if (isset($queryParam['zone'])) {
				$dataQuery = $dataQuery->where('group3', $queryParam['zone']);
			}
			if (isset($queryParam['region'])) {
				$dataQuery = $dataQuery->where('group2', $queryParam['region']);
			}
			if (isset($queryParam['branch'])) {
				$dataQuery = $dataQuery->where('group1', $queryParam['branch']);
			}

			if (isset($queryParam['call_direction'])) {
				$dataQuery = $dataQuery->where('call_direction', $queryParam['call_direction']);
			} else {
				$dataQuery = $dataQuery->where('call_direction', 'incoming');
			}

			if (isset($queryParam['user'])) {
				$userId = User::find($queryParam['user']);
				if ($queryParam['call_direction'] === 'incoming') {
					$dataQuery = $dataQuery->where('from_number', $userId->phone_number);
				} else {
					$dataQuery = $dataQuery->where('to_number', $userId->phone_number);
				}
			}

			$data = $dataQuery->get();

			$array[] = ['Call_Status', 'Number'];
			foreach ($data as $key => $value) {
				$array[++$key] = [$value->callStatus, $value->number];
			}
			$callRecords = json_encode($array);

			$zoneData = isset($queryParam['zone']) ? $this->_zoneData($queryParam) : $this->_zoneData($user->group1);
			$zones = $zoneData['zones'];

			$regionData = isset($queryParam['region']) ? $this->_regionData($queryParam) : $this->_regionData($zoneData['zoneParam']);
			$regions =  $regionData['region'];

			$branchData = isset($queryParam['branch']) ? $this->_branchData($queryParam) : $this->_branchData($regionData['regionParam']);
			$branchs =  $branchData['branch'];

			$users = isset($queryParam['user']) ? $this->_userData($queryParam) : $this->_userData($branchData['branchParam']);

			if ($request->ajax()) {
				return compact('callRecords', 'zones', 'regions', 'branchs', 'users');
			}
			return view('callRecord.google-pie-chart')->with(compact('callRecords', 'zones', 'regions', 'branchs', 'users'));
		
	}

	public function _zoneData($param)
	{
		if (isset($param['zone'])) {
			$query = ZoneMaster::where('id', $param['zone']);
		} else {
			$query = ZoneMaster::where('mega_zone_id', $param);
		}
		$data['zones'] = $query->pluck('zone_name', 'id');
		$data['zoneParam'] = $query->pluck('id')->toArray();
		return $data;
	}

	public function _regionData($param)
	{
		if (isset($param['region'])) {
			$query = RegionMaster::where('id', $param['region']);
		} else {
			$query = RegionMaster::whereIn('zone_id', $param);
		}
		$data['region'] = $query->pluck('region_name', 'id');
		$data['regionParam'] = $query->pluck('id')->toArray();
		return $data;
	}

	public function _branchData($param)
	{
		if (isset($param['branch'])) {
			$query = BranchMaster::where('id', $param['branch']);
		} else {
			$query = BranchMaster::whereIn('region_id', $param);
		}
		$data['branch'] = $query->pluck('branch_name', 'id');
		$data['branchParam'] = $query->pluck('id')->toArray();
		return $data;
	}

	public function _userData($param)
	{
		if (isset($param['user'])) {
			$query = User::where('id', $param['user']);
		} else {
			$query = User::whereIn('group4', $param);
		}
		$user = $query->pluck('name', 'id');
		return $user;
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
