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
use Illuminate\Support\Facades\Validator;

class CallRecordingController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth')->except(['store', 'show']);
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
			$validator = Validator::make($request->all(), [
				'to_number' => 'required',
				'from_number' => 'required'
			]);

			if ($validator->fails()) {
				$response['status'] = 422;
				$response['error'] = $validator->getMessageBag();
				return response()->json($response);
			}
			$requestDatas = $request->input();
			$requestDatas['created_at'] = Carbon::now();

			if (strpos($request->path(), 'Incoming') !== false) {
				$userData = User::where('phone_number', $requestDatas['to_number'])->first();
				$requestDatas['call_directions'] = 'Incoming';
			} else {
				$userData = User::where('phone_number', $requestDatas['from_number'])->first();
				$requestDatas['call_directions'] = 'Outgoing';
			}

			if (!$userData) {
				$response['status'] = 400;
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
		$start = (isset($inputs['start'])) ? $inputs['start'] : 0;
		$length = isset($inputs['length']) ? $inputs['length'] : 10;
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
		$selectOption = [];
		if ($request->ajax()) {
			$dataQuery = CallRecording::group($user->group4)
				->select(
					DB::raw('call_status as callStatus'),
					DB::raw('count(*) as number')
				)
				->groupBy('call_status')
				->where('created_at', '>=', Carbon::today());

			if (isset($queryParam['zone']) && $queryParam['zone']) {
				$dataQuery = $dataQuery->where('group3', $queryParam['zone']);
				$selectOption['zone'] = $queryParam['zone'];
			}
			if (isset($queryParam['region']) && $queryParam['region']) {
				$dataQuery = $dataQuery->where('group2', $queryParam['region']);
				$selectOption['zone'] = RegionMaster::where("id", $queryParam['region'])->first()->zone_id;
				$selectOption['region'] = $queryParam['region'];
			}
			if (isset($queryParam['branch']) && $queryParam['branch']) {
				$dataQuery = $dataQuery->where('group1', $queryParam['branch']);
				$selectOption['zone'] = BranchMaster::where("id", $queryParam['branch'])->first()->region_id;
				$selectOption['region'] = RegionMaster::where("id",	$selectOption['zone'])->first()->zone_id;
				$selectOption['branch'] = $queryParam['branch'];
			}

			if (isset($queryParam['call_direction']) && $queryParam['call_direction']) {
				$dataQuery = $dataQuery->where('call_direction', $queryParam['call_direction']);
				$selectOption['call_direction'] = $queryParam['call_direction'];
			} else {
				$dataQuery = $dataQuery->where('call_direction', 'Incoming');
			}
		

			if (isset($queryParam['user']) && $queryParam['user']) {
				$userId = User::find($queryParam['user']);
				if ($queryParam['call_direction'] === 'Incoming') {
					$dataQuery = $dataQuery->where('from_number', $userId->phone_number);
				} else {
					$dataQuery = $dataQuery->where('to_number', $userId->phone_number);
				}
				$selectOption['zone'] = $userId->group3;
				$selectOption['region'] = $userId->group2;
				$selectOption['branch'] = $userId->group1;
				$selectOption['user'] = $queryParam['user'];
			}

			$data = $dataQuery->get();

			$array[] = ['Call_Status', 'Number'];
			foreach ($data as $key => $value) {
				$array[++$key] = [$value->callStatus, $value->number];
			}
		}
		

		$zoneData = isset($queryParam['zone']) ? $this->_zoneData($queryParam) : $this->_zoneData($user->group4);
		$zone = $zoneData['zones'];

		$regionData = isset($queryParam['region']) ? $this->_regionData($queryParam) : $this->_regionData($zoneData['zoneParam']);
		$region =  $regionData['region'];

		$branchData = isset($queryParam['branch']) ? $this->_branchData($queryParam) : $this->_branchData($regionData['regionParam']);
		$branch =  $branchData['branch'];

		$user = isset($queryParam['user']) ? $this->_userData($queryParam) : $this->_userData($branchData['branchParam']);
		$call_direction = $this->_callDirection();

		if ($request->ajax()) {
			$callRecords = json_encode($array);
			return compact('callRecords', 'zone', 'region', 'branch', 'user', 'call_direction', 'selectOption');
		}
		return view('callRecord.google-pie-chart')->with(compact('zone', 'region', 'branch', 'user', 'call_direction'));
	}

	public function _zoneData($param)
	{
		if (isset($param['zone'])) {
			$query = ZoneMaster::where('id', $param['zone']);
		} else {
			$query = ZoneMaster::where('megazone_id', $param);
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
		$data['branch'] = $query->pluck('branch_code', 'id');
		$data['branchParam'] = $query->pluck('id')->toArray();
		return $data;
	}

	public function _callDirection()
	{
		$call_direction = ['incoming' => 'Incoming', 'outgoing' => 'Outgoing'];
		return $call_direction;
	}
	public function _userData($param)
	{
		if (isset($param['user'])) {
			$query = User::where('id', $param['user']);
		} else {
			$query = User::whereIn('group1', $param);
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

	public function dropDownOption(Request $request)
	{
		$queryParam = $request->all();
		$user = Session::get('user');
		$zoneData = isset($queryParam['zone_summary']) ? $this->_zoneData($queryParam) : $this->_zoneData($user->group4);
		$zone = $zoneData['zones'];
		$selectOption['call_direction_summary'] = $queryParam['call_direction_summary'];
		if (isset($queryParam['region_summary']) && $queryParam['region_summary']) {
			$regionData = $this->_regionData($queryParam);
			$selectOption['zone_summary'] = RegionMaster::where("id", $queryParam['region_summary'])->first()->zone_id;
		} else {
			$regionData = $this->_regionData($zoneData['zoneParam']);
		}
		$region =  $regionData['region'];

		if (isset($queryParam['branch_summary']) && $queryParam['branch_summary']) {
			$branchData = $this->_branchData($queryParam);
			$selectOption['region_summary'] = BranchMaster::where("id", $queryParam['branch_summary'])->first()->region_id;
			$selectOption['zone_summary'] = RegionMaster::where("id",	$selectOption['region_summary'])->first()->zone_id;
			$selectOption['call_direction_summary'] = $queryParam['call_direction_summary'];
		} else {
			$branchData = $this->_branchData($regionData['regionParam']);
		}
		$branch =  $branchData['branch'];

		if (isset($queryParam['user_summary']) && $queryParam['user_summary']) {
			$userId = User::find($queryParam['user_summary']);
			$user =	$this->_userData($queryParam);
			$selectOption['zone_summary'] = $userId->group3;
			$selectOption['region_summary'] = $userId->group2;
			$selectOption['branch_summary'] = $userId->group1;
			$selectOption['user_summary'] = $queryParam['user_summary'];
			$selectOption['call_direction_summary'] = $queryParam['call_direction_summary'];
		} else {
			$user =	$this->_userData($branchData['branchParam']);
		}
		$call_direction = $this->_callDirection();

		$data['zone_summary'] = $zone;
		$data['region_summary'] = $region;
		$data['branch_summary'] = $branch;
		$data['user_summary'] = $user;
		$data['call_direction_summary'] = $call_direction;
		$data['selectOption'] = $selectOption;
		return $data;
	}


	function showData(Request $request)
	{
		$queryParam = $request->all();
		$offSet = isset($queryParam['start'])?$queryParam['start']:0;
		//table data for datatable
		$userDataQuery = User::where('can_make_calls', 'YES');
		if(isset($queryParam['zone_summary']) && $queryParam['zone_summary']) {
			$userDataQuery =  $userDataQuery->where('group3',$queryParam['zone_summary']);
		}
		if(isset($queryParam['region_summary']) && $queryParam['region_summary']) {
			$userDataQuery =  $userDataQuery->where('group2',$queryParam['region_summary']);
		}
		if(isset($queryParam['branch_summary']) && $queryParam['branch_summary']) {
			$userDataQuery =  $userDataQuery->where('group1',$queryParam['branch_summary']);
		}
		$userData = $userDataQuery->skip($offSet)->take(10)->get(['id', 'phone_number', 'name'])->keyBy('phone_number')->toArray();
		$phoneNumberData = array_keys($userData);
		$callRecords = [];
		if (isset($queryParam['call_direction_summary'])) {
			$callRecordQuery = CallRecording::select('from_number', 	DB::raw('COUNT(call_status) as call_status_count'), 'call_status',	DB::raw('SUM(call_duration) as sum_call_status'))->whereBetween('created_at', [Carbon::parse($queryParam['StartDate'])->format('Y-m-d') . " 00:00:00", Carbon::parse($queryParam['EndDate'])->format('Y-m-d') . " 23:59:59"]);
			$key = ($queryParam['call_direction_summary'] == 'Incoming') ? 'to_number' : 'from_number';
			$callRecordQuery = $callRecordQuery->whereIn($key, $phoneNumberData)->where('call_direction', $queryParam['call_direction_summary'])->groupBy('call_status', $key);
			$callRecords = $callRecordQuery->get();
		} 
		if (count($callRecords)) {
			foreach ($callRecords as $key => $value) {
				$userData[$value['from_number']][$value['call_status']] = $value['call_status_count'];
				$userData[$value['from_number']]['total_call'] = (isset($userData[$value['from_number']]['total_call']) ? $userData[$value['from_number']]['total_call']  : 0) + $value['call_status_count'];
				$userData[$value['from_number']]['total_duration'] = (isset($userData[$value['from_number']]['total_duration']) ? $userData[$value['from_number']]['total_duration']  : 0) + $value['sum_call_status'];
				$userData[$value['from_number']]['avg_duration'] = round($userData[$value['from_number']]['total_duration'] / $userData[$value['from_number']]['total_call']);
			}
			$userData = array_values($userData);
			$callData=[];
			for ($i = 0; $i < count($userData); $i++) {
				array_push($callData, [$userData[$i]['id'], $userData[$i]['phone_number'], $userData[$i]['name'], $userData[$i]['total_call'], $userData[$i]['total_duration'], $userData[$i]['avg_duration'],isset($userData[$i]['incompleted'])?$userData[$i]['incompleted']:0, isset($userData[$i]['busy'])?$userData[$i]['busy']:0, isset($userData[$i]['failed'])?$userData[$i]['failed']:0,isset($userData[$i]['completed'])?$userData[$i]['completed']:0]);
			}
		} else {
			$callData = [];
		}

		$response = [
			'draw' => 0,
			'recordsTotal' => count($callData),
			"recordsFiltered" => count($callData),
			"data" =>
			$callData
		];
		return json_encode($response);
	}
}
