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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CallRecordingController extends Controller
{
	public function __construct()
	{
		//$this->middleware('auth')->except(['store', 'show', 'storeFromGet']);
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view('callRecord.index');
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


	private function get_string($str, $param)
	{

		$whatIWant = substr($str, strpos($str, $param));
		$arr = explode("-", $whatIWant, 2);
		$first = $arr[0];
		$strings = str_replace($param, "", $first);
		$finalString = str_replace("\"", "", $strings);

		if (empty($finalString)) {
			return "-";
		}
		return $finalString;
	}


	public function storeFromGet(Request $request)
	{
		try {
			$getData = $request->all();
			if(env("ISWRITELOG") === "YES"){
				Log::info("==========================ALL Request Body Incoming================================================");
				Log::info(json_encode($request->all()));
				Log::info("==========================ALL Request header Incoming================================================");
				Log::info(json_encode($request->header()));
				Log::info("==========================================================================");
			}
			//print_r($getData);exit();
			$insertData = [];
			$insertData['call_sid'] = $getData['CallSid'];
			$insertData['from_number'] = $getData['CallFrom'];
			$insertData['to_number'] = isset($getData['DialWhomNumber']) ? $getData['DialWhomNumber'] : "-";
			$insertData['call_direction'] = "Incoming";
			$insertData['call_recording_link'] = (isset($getData["RecordingUrl"])) ? $getData["RecordingUrl"] : "-";
			$insertData['date_time'] = Date("Y-m-d H:i:s", strtotime($getData["Created"]));
			$insertData['dial_call_duration'] = $getData["DialCallDuration"];

			if (strpos($request->path(), 'NoDial_Call_Details')) {
				$insertData['call_status'] =strtolower("No Answer") ;
				$insertData['call_duration'] = 0;
			}

			if (strpos($request->path(), 'Unanswered_Call_Details')) {
				$insertData['call_status'] = strtolower($getData['CallType']);
				$insertData['call_duration'] = $getData["Legs"][0]['OnCallDuration'];
			}

			if (strpos($request->path(), 'Answered_Call_Details')) {
				$insertData['call_status'] = strtolower($getData['CallType']);
				$insertData['call_duration'] = $getData["Legs"][0]['OnCallDuration'];
			}

			$userData = User::where('phone_number', $getData['CallTo'])->first();

			// if (!$userData) {
			// 	$response['status'] = 400;
			// 	$response['error'] = "No Records match with phone number";
			// 	return response()->json($response);
			// }

			$insertData['agent_name'] = isset($userData['name']) ? $userData['name'] : '-';
			$insertData['agent_phone_number'] = isset($userData['phone_number']) ? $userData['phone_number'] : "-" ;
			$insertData['user_id'] = isset($userData['id']) ? $userData['id'] : 0;
			$insertData['group1'] = isset($userData['group1']) ?  $userData['group1'] : 0 ;
			$insertData['group2'] = isset($userData['group2'])  ?  $userData['group2'] : 0 ;
			$insertData['group3'] = ($userData['group3']) ?$userData['group3']: 0;
			$insertData['group4'] = $userData['group4']? $userData['group4'] : 0; 
			$data = CallRecording::create($insertData);
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
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		try {
			// Now we can get the content from it
			$postData = $request->getContent();
			if(env("ISWRITELOG") === "YES"){
				Log::info("==========================ALL Request Body Outgoing================================================");
				Log::info(json_encode($request->all()));
				Log::info("==========================ALL Request header Outgoing================================================");
				Log::info(json_encode($request->header()));
				Log::info("==========================================================================");
			}
			//print_r($postData);exit();
			$insertData = [];
			$insertData['call_sid'] = $this->get_string($postData, 'CallSid');
			$insertData['from_number'] = $this->get_string($postData, 'From');
			$insertData['to_number'] = $this->get_string($postData, 'To');
			$insertData['call_direction'] = "Outgoing";
			$insertData['call_recording_link'] = $this->get_string($postData, 'RecordingUrl');;
			$insertData['dial_call_duration'] = $this->get_string($postData, 'ConversationDuration');
			$insertData['call_duration'] = $this->get_string($postData, 'Legs[0][OnCallDuration]');
			$insertData['date_time'] = Date("Y-m-d H:i:s", strtotime($this->get_string($postData, 'StartTime')));
			$insertData['call_status'] = strtolower($this->get_string($postData, 'Legs[0][Status]'));
			//print_r($insertData);exit();
			$userData = User::where('phone_number', $insertData['from_number'])->first();

			// if (!$userData) {
			// 	$response['status'] = 400;
			// 	$response['error'] = "No Records match with phone number";
			// 	return response()->json($response);
			// }

			$insertData['agent_name'] = isset($userData['name'])  ? $userData['name'] : '-';
			$insertData['agent_phone_number'] = isset($userData['phone_number']) ? $userData['phone_number'] : '-';
			$insertData['user_id'] = isset($userData['id']) ?  $userData['id'] : 0;
			$insertData['group1'] = isset($userData['group1']) ? $userData['group1'] :0 ;
			$insertData['group2'] = isset($userData['group2']) ? $userData['group2'] : 0;
			$insertData['group3'] = isset($userData['group3']) ? $userData['group3'] : 0 ;
			$insertData['group4'] = isset($userData['group4']) ? $userData['group4'] : 0 ;
			//print_r($insertData);exit();
			$data = CallRecording::create($insertData);
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
			$totalCalls =  CallRecording::group($user->group4)->whereDate('created_at', '=', Carbon::today())->count();
			$totalDurationCalls = CallRecording::group($user->group4)->whereDate('created_at', '=', Carbon::today())->sum('call_duration');
			$avgCalls = 0;
			if($totalDurationCalls) {
				$avgCalls = round($totalDurationCalls / $totalCalls);
			}
			$dataQuery = CallRecording::group($user->group4)
				->select(
					DB::raw('call_status as callStatus'),
					DB::raw('count(*) as number')
				)
				->groupBy('call_status')
				->whereDate('created_at', '=', Carbon::today());

			if (isset($queryParam['zone']) && $queryParam['zone']) {
				$dataQuery = $dataQuery->where('group3', $queryParam['zone']);
				$selectOption['zone'] = $queryParam['zone'];
			}
			if (isset($queryParam['region']) && $queryParam['region']) {
				$dataQuery = $dataQuery->where('group2', $queryParam['region']);
				$selectOption['region'] = $queryParam['region'];
			}
			if (isset($queryParam['branch']) && $queryParam['branch']) {
				$dataQuery = $dataQuery->where('group1', $queryParam['branch']);
				$selectOption['branch'] = $queryParam['branch'];
			}

			if (isset($queryParam['call_direction']) && $queryParam['call_direction']) {
				$dataQuery = $dataQuery->where('call_direction', $queryParam['call_direction']);
				$selectOption['call_direction'] = $queryParam['call_direction'];
			}

			
			if (isset($queryParam['user']) && $queryParam['user']) {
				$userId = User::find($queryParam['user']);
				switch($queryParam['call_direction']){
					case 'Incoming':
						$dataQuery = $dataQuery->where('from_number', $userId->phone_number);	
					break;

					case 'Outgoing':
						$dataQuery = $dataQuery->where('to_number', $userId->phone_number);	
					break;

				}
				$selectOption['user'] = $queryParam['user'];
			}

			$data = $dataQuery->get();

			$array[] = ['Call_Status', 'Number'];
			foreach ($data as $key => $value) {
				$array[++$key] = [ucwords($value->callStatus), $value->number];
			}
		}


		$zoneData = $this->_zoneData($user->group4);
		$zone = $zoneData['zones'];

		$regionData = (isset($queryParam['zone']) && $queryParam['zone']) ? $this->_regionData($queryParam) : $this->_regionData($zoneData['zoneParam']);
		$region =  $regionData['region'];

		$branchData = (isset($queryParam['region']) && $queryParam['region']) ? $this->_branchData($queryParam) : $this->_branchData($regionData['regionParam']);
		$branch =  $branchData['branch'];

		$user = (isset($queryParam['branch']) && $queryParam['branch']) ? $this->_userData($queryParam) : $this->_userData($branchData['branchParam']);
		$call_direction = $this->_callDirection();

		if ($request->ajax()) {
			$callRecords = json_encode($array);
			return compact('callRecords', 'zone', 'region', 'branch', 'user', 'call_direction', 'selectOption', 'totalCalls', 'totalDurationCalls', 'avgCalls');
		}
		return view('callRecord.google-pie-chart')->with(compact('zone', 'region', 'branch', 'user', 'call_direction'));
	}

	public function _zoneData($param)
	{
		$query = ZoneMaster::where('megazone_id', $param);
		$data['zones'] = $query->pluck('zone_name', 'id');
		$data['zoneParam'] = $query->pluck('id')->toArray();
		return $data;
	}

	public function _regionData($param)
	{
		if (isset($param['zone'])) {
			$query = RegionMaster::where('zone_id', $param['zone']);
		} else {
			$query = RegionMaster::whereIn('zone_id', $param);
		}
		$data['region'] = $query->pluck('region_name', 'id');
		$data['regionParam'] = $query->pluck('id')->toArray();
		return $data;
	}

	public function _branchData($param)
	{
		if (isset($param['region'])) {
			$query = BranchMaster::where('region_id', $param['region']);
		} else {
			$query = BranchMaster::whereIn('region_id', $param);
		}
		$data['branch'] = $query->pluck('branch_code', 'id');
		$data['branchParam'] = $query->pluck('id')->toArray();
		return $data;
	}

	public function _callDirection()
	{
		$call_direction = ['Incoming' => 'Incoming', 'Outgoing' => 'Outgoing'];
		return $call_direction;
	}

	public function _userData($param)
	{
		if (isset($param['branch'])) {
			$query = User::where('group1', $param['branch']);
		} else {
			$query = User::whereIn('group1', $param);
		}
		$query = $query->where('can_make_call', "YES");
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
		$zoneData = $this->_zoneData($user->group4);
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
		$offSet = isset($queryParam['start']) ? $queryParam['start'] : 0;

		$callData = [];

		$callRecordQuery = CallRecording::select('user_id', 'agent_name', 'agent_phone_number', 	DB::raw('COUNT(call_status) as call_status_count'), 'call_status',	DB::raw('SUM(call_duration) as sum_call_status'))->whereBetween('created_at', [Carbon::parse($queryParam['StartDate'])->format('Y-m-d') . " 00:00:00", Carbon::parse($queryParam['EndDate'])->format('Y-m-d') . " 23:59:59"]);
		if (isset($queryParam['zone_summary']) && $queryParam['zone_summary']) {
			$callRecordQuery =  $callRecordQuery->where('group3', $queryParam['zone_summary']);
		}
		if (isset($queryParam['region_summary']) && $queryParam['region_summary']) {
			$callRecordQuery =  $callRecordQuery->where('group2', $queryParam['region_summary']);
		}
		if (isset($queryParam['branch_summary']) && $queryParam['branch_summary']) {
			$callRecordQuery =  $callRecordQuery->where('group1', $queryParam['branch_summary']);
		}
		if (isset($queryParam['call_direction_summary']) && $queryParam['call_direction_summary'] != 'undefined') {
			$callRecordQuery = $callRecordQuery->where('call_direction', $queryParam['call_direction_summary']);
		}
		if (isset($queryParam['user_summary']) && $queryParam['user_summary']) {
			$callRecordQuery = $callRecordQuery->where('user_id', $queryParam['user_summary']);
		}
		$callRecordQuery = $callRecordQuery->groupBy('agent_phone_number', 'user_id', 'agent_name', 'call_status');
		//$callRecordQuery =  $callRecordQuery->orderBy('created_at', 'desc');
		$callRecordData = $callRecordQuery->get();

		$callRecordNumber = $callRecordData->keyBy('agent_phone_number')->toArray();
		$callRecords = [];
		$userIdData = '';
		if (count($callRecordData)) {
			foreach ($callRecordData as $key => $value) {
				$callRecordNumber[$value['agent_phone_number']][$value['call_status']] = $value['call_status_count'];
				$callRecordNumber[$value['agent_phone_number']]['call_count'] = (isset($callRecordNumber[$value['agent_phone_number']]['call_count']) ? $callRecordNumber[$value['agent_phone_number']]['call_count']  : 0) + $value['call_status_count'];
				$callRecordNumber[$value['agent_phone_number']]['total_durations'] = (isset($callRecordNumber[$value['agent_phone_number']]['total_durations']) ? $callRecordNumber[$value['agent_phone_number']]['total_durations']  : 0) + $value['sum_call_status'];
				$callRecordNumber[$value['agent_phone_number']]['avg_durations'] = round($callRecordNumber[$value['agent_phone_number']]['total_durations'] / $callRecordNumber[$value['agent_phone_number']]['call_count']);
			}

			$userData = array_values($callRecordNumber);
			$callData = [];

			for ($i = 0; $i < count($userData); $i++) {
				array_push($callData, [$userData[$i]['agent_name'],  $userData[$i]['agent_phone_number'],  $userData[$i]['call_count'], $userData[$i]['total_durations'], $userData[$i]['avg_durations'],  isset($userData[$i]['completed']) ? $userData[$i]['completed'] : 0,isset($userData[$i]['no answer']) ? $userData[$i]['no answer'] : 0,isset($userData[$i]['busy']) ? $userData[$i]['busy'] : 0, isset($userData[$i]['failed']) ? $userData[$i]['failed'] : 0]);//, isset($userData[$i]['client-hangup']) ? $userData[$i]['client-hangup'] : 0
				$userIdData .= ($i == 0) ? $userData[$i]['user_id'] : ',' . $userData[$i]['user_id'];
			}
		}
		$data['callData'] = $callData;
		$data['userId'] = $userIdData;
		return json_encode($data);
	}

	public function detailList(Request $request)
	{
		$queryParam = $request->all();

		$callData = [];

		$callRecordQuery = CallRecording::select('id', 'call_sid','user_id', 'agent_name', 'agent_phone_number', 'from_number', 'to_number', 'call_duration', 'call_status', 'call_direction','date_time','dial_call_duration','call_recording_link')->whereBetween('created_at', [Carbon::parse($queryParam['StartDate'])->format('Y-m-d') . " 00:00:00", Carbon::parse($queryParam['EndDate'])->format('Y-m-d') . " 23:59:59"]);

		if (isset($queryParam['zone']) && $queryParam['zone']) {
			$callRecordQuery = $callRecordQuery->where('group3', $queryParam['zone']);
		}
		if (isset($queryParam['region']) && $queryParam['region']) {
			$callRecordQuery = $callRecordQuery->where('group2', $queryParam['region']);
		}
		if (isset($queryParam['branch']) && $queryParam['branch']) {
			$callRecordQuery = $callRecordQuery->where('group1', $queryParam['branch']);
		}

		if (isset($queryParam['call_direction']) && $queryParam['call_direction']) {
			$callRecordQuery = $callRecordQuery->where('call_direction', $queryParam['call_direction']);
			//$selectOption['call_direction'] = $queryParam['call_direction'];
		}


		if (isset($queryParam['user']) && $queryParam['user']) {
			$userId = User::find($queryParam['user']);
			if ($queryParam['call_direction'] === 'Incoming') {
				$callRecordQuery = $callRecordQuery->where('from_number', $userId->phone_number);
			} else {
				$callRecordQuery = $callRecordQuery->where('to_number', $userId->phone_number);
			}
			$selectOption['user'] = $queryParam['user'];
		}


		if (isset($queryParam['call_status']) && $queryParam['call_status'] != "undefined" && $queryParam['call_status']) {	
			$callRecordQuery = $callRecordQuery->whereIn('call_status', explode(",",$queryParam['call_status']));
		}
		$callRecordQuery =  $callRecordQuery->orderBy('created_at', 'desc');
		$userData = $callRecordQuery->get();
		for ($i = 0; $i < count($userData); $i++) {
			if(strtolower($userData[$i]['call_direction']) === 'outgoing'){
				$cust_number = $userData[$i]['to_number'];
			}else{
				$cust_number = $userData[$i]['from_number'];
			}
			$link = "";
			if($userData[$i]['call_recording_link'] != '-'){
				$link  = "<audio controls><source src='".$userData[$i]['call_recording_link']."'> </audio>";
			}
			array_push($callData, [$userData[$i]['agent_name'],$userData[$i]['agent_phone_number'],$cust_number, Date("Y-m-d H:i:s",strtotime($userData[$i]['date_time'])),$userData[$i]['call_direction'], ucwords($userData[$i]['call_status']), $userData[$i]['call_sid'], $userData[$i]['call_duration'], $userData[$i]['dial_call_duration'],
			 $link]);
		}
		return response()->json($callData);
	}
}
