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
	/**
	 * constructor
	 */
	public function __construct()
	{
		$this->middleware('auth')->except(['store', 'show', 'storeFromGet']);
		$this->user = Session::get('user');
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
	 * get string value private function for get values in outgoing call
	 * $str post string 
	 * $params paramter to take value
	 */
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

	/**
	 * Store call records which are from incoming call diraction
	 * $request Request Parameter to get input  
	 */

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
			$insertData['date_time'] = Date("Y-m-d H:i:s", strtotime(urldecode($getData["StartTime"])));
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

			$userData = User::where('phone_number', $insertData['to_number'])->first();

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
	 * call record store for outgoing call .
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		try {
			// Now we can get the content from it
			$postData = $request->all();
			if(env("ISWRITELOG") === "YES"){
				Log::info("==========================ALL Request Body Outgoing================================================");
				Log::info(json_encode($request->all()));
				Log::info(json_encode($request->getContent()));
				Log::info("==========================ALL Request header Outgoing================================================");
				Log::info(json_encode($request->header()));
				Log::info("==========================================================================");
			}
			
			$insertData = [];
			$insertData['call_sid'] = $postData['CallSid'];//$this->get_string($postData, 'CallSid');
			$insertData['from_number'] = $postData['From'];//$this->get_string($postData, 'From');
			$insertData['to_number'] = $postData['To']; //$this->get_string($postData, 'To');
			$insertData['call_direction'] = "Outgoing";
			$insertData['call_recording_link'] = $postData["RecordingUrl"];//$this->get_string($postData, 'RecordingUrl');;
			$insertData['dial_call_duration'] = $postData["ConversationDuration"]; //$this->get_string($postData, 'ConversationDuration');
			$insertData['call_duration'] = $postData['Legs'][0]['OnCallDuration']; // $this->get_string($postData, 'Legs[0][OnCallDuration]');
			$insertData['date_time'] = Date("Y-m-d H:i:s", strtotime(urldecode($postData['StartTime'])));
			$insertData['call_status'] = strtolower($postData['Legs'][0]["Status"]);
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
	 * method for show pie chart details .
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\CallRecording  $callRecording
	 * @return \Illuminate\Http\Response
	 */
	public function pieChart(Request $request)
	{
		$selectOption = [];
		$queryParam = $request->all();
		$user = Session::get('user');
		if(isset($user->group3) && $user->group3 !== 0 ){
			$selectOption['zone'] = $user->group3;
			$queryParam['zone'] =  $user->group3;
		}
		if(isset($user->group2) && $user->group2 !== 0 ){
			$selectOption['region'] = $user->group2;
			$queryParam['region'] = $user->group2;
		}
		if(isset($user->group1) && $user->group1 !== 0 ){
			$selectOption['branch'] = $user->group1;
			$queryParam['branch'] = $user->group1;
		}
		
		if ($request->ajax()) {
			$totalCalls =  CallRecording::group($user->group4)
							->Filter("group3", $user->group3)
							->Filter("group2", $user->group2)
							->Filter("group1", $user->group1)
							->whereDate('created_at', '=', Carbon::today());

			if (isset($queryParam['zone']) && $queryParam['zone']) {
				//echo $queryParam['zone'] . "-";
				$totalCalls = $totalCalls->where('group3', $queryParam['zone']);
				$selectOption['zone'] = $queryParam['zone'];
			}
			if (isset($queryParam['region']) && $queryParam['region']) {
				//echo ($queryParam['region']. "-");
				$totalCalls = $totalCalls->where('group2', $queryParam['region']);
				$selectOption['region'] = $queryParam['region'];
			}
			if (isset($queryParam['branch']) && $queryParam['branch']) {
				// echo ($queryParam['branch']);
				$totalCalls = $totalCalls->where('group1', $queryParam['branch']);
				$selectOption['branch'] = $queryParam['branch'];
			}
			if (isset($queryParam['call_direction']) && $queryParam['call_direction'] && $queryParam['call_direction'] != '') {
				// echo $queryParam['call_direction'];
				$totalCalls =  $totalCalls->where('call_direction', $queryParam['call_direction']);
			}

			if(isset($queryParam['user']) && $queryParam['user']){
				// echo $queryParam['user'];
				$userId = User::find( $queryParam['user']);	
				$totalCalls =  $totalCalls->where('agent_phone_number', $userId->phone_number);	
			}
			// echo $totalCalls->toSql();exit();
			$totalCalls =  $totalCalls->count();
			
			$totalDurationCalls = CallRecording::group($user->group4)
									->Filter("group3", $user->group3)
									->Filter("group2", $user->group2)
									->Filter("group1", $user->group1)
									->whereDate('created_at', '=', Carbon::today());
			if (isset($queryParam['zone']) && $queryParam['zone']) {
				$totalDurationCalls = $totalDurationCalls->where('group3', $queryParam['zone']);
				$selectOption['zone'] = $queryParam['zone'];
			}
			if (isset($queryParam['region']) && $queryParam['region']) {
				$totalDurationCalls = $totalDurationCalls->where('group2', $queryParam['region']);
				$selectOption['region'] = $queryParam['region'];
			}
			if (isset($queryParam['branch']) && $queryParam['branch']) {
				$totalDurationCalls = $totalDurationCalls->where('group1', $queryParam['branch']);
				$selectOption['branch'] = $queryParam['branch'];
			}
			if (isset($queryParam['call_direction']) && $queryParam['call_direction'] && $queryParam['call_direction'] != '') {
				$totalDurationCalls = $totalDurationCalls->where('call_direction', $queryParam['call_direction']);
			}

			if(isset($queryParam['user']) && $queryParam['user']){
				$userId = User::find( $queryParam['user']);	
				$totalDurationCalls = $totalDurationCalls->where('agent_phone_number', $userId->phone_number);	
			}						
			$totalDurationCalls = $totalDurationCalls->sum('call_duration');
			
			$avgCalls = 0;
			if($totalDurationCalls) {
				$avgCalls = round($totalDurationCalls / $totalCalls);
			}
			$dataQuery = CallRecording::group($user->group4)
				->Filter("group3", $user->group3)->Filter("group2", $user->group2)->Filter("group1", $user->group1)
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

			if (isset($queryParam['call_direction']) && $queryParam['call_direction'] && $queryParam['call_direction'] != '') {
				$dataQuery = $dataQuery->where('call_direction', $queryParam['call_direction']);
				$selectOption['call_direction'] = $queryParam['call_direction'];
			}

			
			if (isset($queryParam['user']) && $queryParam['user']) {
				$userId = User::find( $queryParam['user']);
				
				$dataQuery = $dataQuery->where('agent_phone_number', $userId->phone_number);
				
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
			// dd($zone);
			return compact('callRecords', 'zone', 'region', 'branch', 'user', 'call_direction', 'selectOption', 'totalCalls', 'totalDurationCalls', 'avgCalls');
		}
		return view('callRecord.google-pie-chart')->with(compact('zone', 'region', 'branch', 'user', 'call_direction'));
	}

	/**
	 * get zone data based
	 */
	public function _zoneData($param)
	{
		$query = ZoneMaster::ZoneData(); //where('megazone_id', $param);
		$query =  $query->orderBy('zone_name', 'asc');
		$data['zones'] = $query->pluck('zone_name', 'id');
		
		$data['zoneParam'] = $query->pluck('id')->toArray();
		return $data;
	}
	/**
	 * getregional data
	 */
	public function _regionData($param)
	{
		$query = RegionMaster::RegoinData();
		if (isset($param['zone'])) {
			$query = $query->where('zone_id', $param['zone']);
		} else {
			$query = $query->whereIn('zone_id', $param);
		}
		$query =  $query->orderBy('region_name', 'asc');
		$data['region'] = $query->pluck('region_name', 'id');
		$data['regionParam'] = $query->pluck('id')->toArray();
		// dd($data);
		return $data;
	}

	/**
	 * get branch data 
	 */
	public function _branchData($param)
	{
		$query = BranchMaster::BranchData();
		if (isset($param['region'])) {
			$query = $query->where('region_id', $param['region']);
		} else {
			$query = $query->whereIn('region_id', $param);
		}
		$query =  $query->orderBy('branch_code', 'asc');
		$data['branch'] = $query->pluck('branch_code', 'id');
		$data['branchParam'] = $query->pluck('id')->toArray();
		return $data;
	}

	/**
	 * call redirection 
	 */
	public function _callDirection()
	{
		$call_direction = ['Incoming' => 'Incoming', 'Outgoing' => 'Outgoing'];
		return $call_direction;
	}
	/**
	 * users in brach 
	 */
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
	/**
	 * set dropdown option 
	 */
	public function dropDownOption(Request $request)
	{
		$queryParam = $request->all();
		$user = Session::get('user');
		$zoneData = $this->_zoneData($user->group4);
		$zone = $zoneData['zones'];

		$selectOption['call_direction'] = $queryParam['call_direction'];
		if (isset($queryParam['region']) && $queryParam['region'] && $queryParam['region'] != 'null') {
			$regionData = $this->_regionData($queryParam);
			$selectOption['zone'] = RegionMaster::where("id", $queryParam['region'])->first()->zone_id;
		} else {
			$regionData = $this->_regionData($zoneData['zoneParam']);
		}
		$region =  $regionData['region'];

		if (isset($queryParam['branch']) && $queryParam['branch']) {
			$branchData = $this->_branchData($queryParam);
			$selectOption['region'] = BranchMaster::where("id", $queryParam['branch'])->first()->region_id;
			$selectOption['zone'] = RegionMaster::where("id",	$selectOption['region'])->first()->zone_id;
			$selectOption['call_direction'] = $queryParam['call_direction'];
		} else {
			$branchData = $this->_branchData($regionData['regionParam']);
		}
		$branch =  $branchData['branch'];

		if (isset($queryParam['user']) && $queryParam['user'] && $queryParam['user'] !== 'null') {
			$userId = User::find($queryParam['user']);
			$user =	$this->_userData($queryParam);
			$selectOption['zone'] = $userId->group3;
			$selectOption['region'] = $userId->group2;
			$selectOption['branch'] = $userId->group1;
			$selectOption['user'] = $queryParam['user'];
			$selectOption['call_direction'] = $queryParam['call_direction'];
		} else {
			$user =	$this->_userData($branchData['branchParam']);
		}
		$call_direction = $this->_callDirection();

		$data['zone'] = $zone;
		$data['region'] = $region;
		$data['branch'] = $branch;
		$data['user'] = $user;
		$data['call_direction'] = $call_direction;
		$data['selectOption'] = $selectOption;
		return $data;
	}

	/**
	 * summary report 
	 */
	function showData(Request $request)
	{
		$queryParam = $request->all();
		$offSet = isset($queryParam['start']) ? $queryParam['start'] : 0;

		$callData = [];
		$user  =  Session::get('user'); 
		$callRecordQuery = CallRecording::select('user_id', 'agent_name', 'agent_phone_number', 	DB::raw('COUNT(call_status) as call_status_count'), 'call_status',	DB::raw('SUM(call_duration) as sum_call_status'))->whereBetween('created_at', [Carbon::parse($queryParam['StartDate'])->format('Y-m-d') . " 00:00:00", Carbon::parse($queryParam['EndDate'])->format('Y-m-d') . " 23:59:59"]);
		// $callRecordQuery =  $callRecordQuery->where('group4', $user->group4);
		
		$user = Session::get('user');
		if(isset($user->group3) && $user->group3 !== 0 ){
			$selectOption['zone'] = $user->group3;
			$queryParam['zone'] = $queryParam['zone'] = $user->group3;
		}
		if(isset($user->group2) && $user->group2 !== 0 ){
			$selectOption['region'] = $user->group2;
			$queryParam['region'] = $queryParam['region'] = $user->group2;
		}
		if(isset($user->group1) && $user->group1 !== 0 ){
			$selectOption['branch'] = $user->group1;
			$queryParam['branch'] = $queryParam['branch'] = $user->group1;
		}
		$callRecordQuery = $callRecordQuery->Filter('group4',$user->group4)->Filter('group3',$user->group3)->Filter('group2',$user->group2)->Filter('group1',$user->group1);
		
		if (isset($queryParam['zone']) && $queryParam['zone'] && ($queryParam['zone'] != 'null')) {
			$callRecordQuery = $callRecordQuery->where('group3', $queryParam['zone']);
			$selectOption['zone'] = $queryParam['zone'];
		}
		if (isset($queryParam['region']) && $queryParam['region'] && ($queryParam['region'] != 'null')) {
			$callRecordQuery = $callRecordQuery->where('group2', $queryParam['region']);
			$selectOption['region'] = $queryParam['region'];
		}
		if (isset($queryParam['branch']) && $queryParam['branch'] && ($queryParam['branch'] != 'null')) {
			$callRecordQuery = $callRecordQuery->where('group1', $queryParam['branch']);
			$selectOption['branch'] = $queryParam['branch'];
		}

		if (isset($queryParam['call_direction']) && !is_null($queryParam['call_direction']) && $queryParam['call_direction'] != "null" && $queryParam['call_direction']) {	
			$callRecordQuery = $callRecordQuery->where('call_direction', $queryParam['call_direction']);
		}
		if (isset($queryParam['user']) && $queryParam['user']) {
			$callRecordQuery = $callRecordQuery->where('user_id', $queryParam['user']);
		}
		$callRecordQuery = $callRecordQuery->groupBy('agent_phone_number', 'user_id', 'agent_name', 'call_status');
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

	/**
	 * Detailed report 
	 */
	public function detailList(Request $request)
	{

		// dd($request['search']['value']);
		$queryParam = $request->all();
		$orderOn[0] = 'agent_name';
		$orderOn[1] = 'agent_phone_number';
		$orderOn[3] = 'created_at';
		$orderOn[4] = 'call_direction';
		$orderOn[5] = 'call_status';
		$orderOn[6] = 'call_sid';
		$orderOn[7] = 'call_duration';
		$orderOn[8] = 'dial_call_duration';
		$orderOn[9] = 'call_recording_link';

		$offSet = isset($queryParam['start']) ? $queryParam['start'] : 0;
		$length = ($queryParam['length'] > 0) ? $queryParam['length'] : 10;
		$callData = [];
		$user = Session::get('user'); 
		
		$callRecordQuery = CallRecording::select('id', 'user_id', 'agent_name', 'agent_phone_number', 'from_number', 'to_number', 'call_duration', 'call_status', 'call_direction','date_time','dial_call_duration','call_recording_link','call_sid');
		$callRecordQuery = $callRecordQuery->whereBetween('created_at', [Carbon::parse($queryParam['StartDate'])->format('Y-m-d') . " 00:00:00", Carbon::parse($queryParam['EndDate'])->format('Y-m-d') . " 23:59:59"]);
		//$callRecordQuery = $callRecordQuery->Filter('group4',$user->group4)->Filter('group3',$user->group3)->Filter('group2',$user->group2)->Filter('group1',$user->group1);
		$user = Session::get('user');
		if(isset($user->group3) && $user->group3 !== 0 ){
			$selectOption['zone'] = $user->group3;
			$queryParam['zone'] = $queryParam['zone'] = $user->group3;
		}
		if(isset($user->group2) && $user->group2 !== 0 ){
			$selectOption['region'] = $user->group2;
			$queryParam['region'] = $queryParam['region'] = $user->group2;
		}
		if(isset($user->group1) && $user->group1 !== 0 ){
			$selectOption['branch'] = $user->group1;
			$queryParam['branch'] = $queryParam['branch'] = $user->group1;
		}
		$callRecordQuery = $callRecordQuery->Filter('group4',$user->group4)->Filter('group3',$user->group3)->Filter('group2',$user->group2)->Filter('group1',$user->group1);
		
		if (isset($queryParam['zone']) && $queryParam['zone'] && ($queryParam['zone'] != 'null')) {
			$callRecordQuery = $callRecordQuery->where('group3', $queryParam['zone']);
			$selectOption['zone'] = $queryParam['zone'];
		}
		if (isset($queryParam['region']) && $queryParam['region'] && ($queryParam['region'] != 'null')) {
			$callRecordQuery = $callRecordQuery->where('group2', $queryParam['region']);
			$selectOption['region'] = $queryParam['region'];
		}
		if (isset($queryParam['branch']) && $queryParam['branch'] && ($queryParam['branch'] != 'null')) {
			$callRecordQuery = $callRecordQuery->where('group1', $queryParam['branch']);
			$selectOption['branch'] = $queryParam['branch'];
		}

		// if (isset($queryParam['call_direction']) && !is_null($queryParam['call_direction']) && $queryParam['call_direction'] != "null" && $queryParam['call_direction']) {	
		// 	$callRecordQuery = $callRecordQuery->where('call_direction', $queryParam['call_direction']);
		// }
		// if (isset($queryParam['user']) && $queryParam['user']) {
		// 	$callRecordQuery = $callRecordQuery->where('user_id', $queryParam['user']);
		// }
		//$callRecordQuery = $callRecordQuery->groupBy('agent_phone_number', 'user_id', 'agent_name', 'call_status');
		
		
		//$callRecordQuery = $callRecordQuery->whereBetween('created_at', [Carbon::parse($queryParam['StartDate'])->format('Y-m-d') . " 00:00:00", Carbon::parse($queryParam['EndDate'])->format('Y-m-d') . " 23:59:59"]);

		if (isset($queryParam['call_direction']) && !is_null($queryParam['call_direction']) && $queryParam['call_direction'] != "null" && $queryParam['call_direction']) {
		$callRecordQuery = $callRecordQuery->where('call_direction', $queryParam['call_direction']);
		$selectOption['call_direction'] = $queryParam['call_direction'];
		}

		if($request['search']['value']){
			$callRecordQuery = $callRecordQuery->where(function($query) use ($request) {
                return $query->where('from_number' , 'like', '%'.$request['search']['value'].'%')
                    ->orWhere('to_number',  'like', '%'.$request['search']['value'].'%');
			});
			
		}

		if (isset($queryParam['user']) && $queryParam['user']) {
			$userId = User::find($queryParam['user']);
			$callRecordQuery = $callRecordQuery->where('agent_phone_number', $userId->phone_number);
			$selectOption['user'] = $queryParam['user'];
		}


		if (isset($queryParam['call_status']) && $queryParam['call_status'] != "undefined" && $queryParam['call_status']) { 
			$callRecordQuery = $callRecordQuery->whereIn('call_status', explode(",",$queryParam['call_status']));
		}
		$totalRecords = $callRecordQuery->count();
		$column = (isset($queryParam['order'][0]['column']) && isset($orderOn[$queryParam['order'][0]['column']])) ? $orderOn[$queryParam['order'][0]['column']] : 'created_at';
		$orderBy  = (isset($queryParam['order'][0]['dir'])  ) ? $queryParam['order'][0]['dir'] : 'desc';		
		$callRecordQuery =  $callRecordQuery->orderBy($column, $orderBy);
		$userData = $callRecordQuery->skip($offSet)->take($length)->get();
		
		for ($i = 0; $i < count($userData); $i++) {
			if(isset($userData[$i]['call_direction'])){
				if(strtolower($userData[$i]['call_direction']) === 'outgoing'){
					$cust_number = $userData[$i]['to_number'];
					$orderOn[2] = 'to_number';
				}else{
					$cust_number = $userData[$i]['from_number'];
					$orderOn[2] = 'from_number';
				}	
			}
			
		$link = "";
		if($userData[$i]['call_recording_link'] != '-'){
			$link  = "<audio controls><source src='".$userData[$i]['call_recording_link']."'> </audio>";
		}
		array_push($callData, ['agent_name'=>$userData[$i]['agent_name'],'agent_phone_number'=>$userData[$i]['agent_phone_number'],'cust_number'=>$cust_number,'date_time'=> Date("Y-m-d H:i:s",strtotime($userData[$i]['date_time'])),'call_direction'=>$userData[$i]['call_direction'],'call_status'=> $userData[$i]['call_status'],'call_sid'=> $userData[$i]['call_sid'], 'call_duration'=>$userData[$i]['call_duration'], 'dial_call_duration'=>$userData[$i]['dial_call_duration'],
		'link'=>$link]);
		}

		$response = [
		'draw' => 0,
		'recordsTotal' => $totalRecords,
		"recordsFiltered" => $totalRecords,
		"data" =>
		$callData
		];

		return response()->json($response);
	}
}
