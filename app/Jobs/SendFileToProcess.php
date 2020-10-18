<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Hash;

use App\User,App\MegaZoneMaster,
App\RegionMaster,
App\BranchMaster,
App\ZoneMaster,
App\FileUpload,
App\UsersLog;


class SendFileToProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
  
    protected $details;
  
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('max_execution_time', 0);
        try{
            //update the status to file processing
            $fileUploadProcessingRecord = [];// dd($fileUploadProcessingRecord);
            $fileUploadProcessingRecord['upload_status'] = 'processing';
            try{
                $userDetails = FileUpload::where('id', $this->details->id)->update($fileUploadProcessingRecord);

                //Start Processing File 
                $filePath = storage_path().'/app/public/'.$this->details->file_name;

                $errorOverAllFlag = false;
                //Process Heirachy 1 by 1 
                $groupData  = (new FastExcel)->sheet(2)->import($filePath);
                if(count($groupData) < 0){
                    $fileUploadProcessingRecord = [];
                    $fileUploadProcessingRecord['upload_status'] = 'failed';
                    $fileUploadProcessingRecord['remark'] = 'Hierachy data not present.';
                    $userDetails = FileUpload::where('id', $this->details->id)->update($fileUploadProcessingRecord);
                    return false;
                }

                $arrHierarchyColumn = ['Group1','Group2','Group3','Group4'];
                $arrDataKeys = array_keys($groupData[0]);
                $diff = array_diff($arrHierarchyColumn,$arrDataKeys);
                if(count($diff) > 0 ){
                    $fileUploadProcessingRecord = [];
                    $fileUploadProcessingRecord['upload_status'] = 'failed';
                    $fileUploadProcessingRecord['remark'] = join(",",$diff).' not present in Hierarchy.';
                    $userDetails = FileUpload::where('id', $this->details->id)->update($fileUploadProcessingRecord);
                    return false;
                }
                //check fields are present or not in hierachy 
                $exportUserList  = (new FastExcel)->sheet(1)->import($filePath);
                if(count($exportUserList) < 0){
                    $fileUploadProcessingRecord = [];
                    $fileUploadProcessingRecord['upload_status'] = 'failed';
                    $fileUploadProcessingRecord['remark'] = 'User data not present.';
                    $userDetails = FileUpload::where('id', $this->details->id)->update($fileUploadProcessingRecord);
                    return false;
                }
                //check fields are present or not in user
                $arrUserColumn = ['Name','Number','Email','Group1','Group2','Group3','Group4', 'Designation'];
                $arrDataKeys = array_keys($exportUserList[0]);
                $diff = array_diff($arrUserColumn,$arrDataKeys);
                if(count($diff) > 0 ){
                    $fileUploadProcessingRecord = [];
                    $fileUploadProcessingRecord['upload_status'] = 'failed';
                    $fileUploadProcessingRecord['remark'] = join(",",$diff).' not present in users List.';
                    $userDetails = FileUpload::where('id', $this->details->id)->update($fileUploadProcessingRecord);
                    return false;
                }

                $this->addHierachyData($groupData);
                
                //users Data Processing 
                $arrAllEmailIDs = [];
                //get all user users's email id to check exist or not 
                $arrEmail = User::all()->keyBy('email')->toArray();
                

                $arrUpdateDateEmailAddress = [];
                
                $errorOverAllFlag = $this->addUserData($exportUserList,$arrEmail);
                
                $fileUploadProcessingRecord = [];// dd($fileUploadProcessingRecord);
                if($errorOverAllFlag){
                    $fileUploadProcessingRecord['upload_status'] = 'completed-with-error';
                }else{
                    $fileUploadProcessingRecord['upload_status'] = 'completed';
                }
                $userDetails = FileUpload::where('id', $this->details->id)->update($fileUploadProcessingRecord);
                
                return true;
            }catch(Exception $e){
                $fileUploadProcessingRecord = [];
                $fileUploadProcessingRecord['upload_status'] = 'failed';
                $fileUploadProcessingRecord['remark'] = $e;
                $userDetails = FileUpload::where('id', $this->details->id)->update($fileUploadProcessingRecord);
                return false;
            }
        }catch(Exception $e){
            $fileUploadProcessingRecord = [];
            $fileUploadProcessingRecord['upload_status'] = 'failed';
            $fileUploadProcessingRecord['remark'] = $e;
            $userDetails = FileUpload::where('id', $this->details->id)->update($fileUploadProcessingRecord);
            return false;
        }
    }

    private function checkgrouplevelWise($userDetails){
        if(isset($userDetails['level']) ){
            $levelGroup = explode(",",env("GROUPS".$userDetails['level']));
            
            forEach($levelGroup as $group){
                
                if($userDetails[$group] === 0){
                    return false;
                }
            }
            return true;
        }else{
            return false;
        }
    }
    /**
     * Export user data 
     */
     private function addUserData($exportUserList,$arrEmail){
        $arrUpdateDateEmailAddress = [];
        $arrAllEmailIDs = array_keys($arrEmail);
        
        $arrNoPortalAccess = explode(",",env('NO_PORTAL_ACCESS'));
        $arrCanMakeCall = explode(",",str_replace(" ",'_SPACE_',env('CAN_MAKE_CALLABLE')));

        $arrMegaZone = $arrBranchCode = $arrZone = $arrRegoin = [];
        $arrMegaZone = MegaZoneMaster::all()->pluck('id','megazone_name');
        $arrZone = ZoneMaster::all()->pluck('id','zone_name');
        $arrRegoin = RegionMaster::all()->pluck('id','region_name');
        $arrBranchCode = BranchMaster::all()->pluck('id','branch_code');
        $errorOverAllFlag = false;
        foreach($exportUserList as  $key => $user ){
            if($user['Email'] === '' && $user['Number'] === '' ){
            break;
            }
            $errorFlag = false;
            $arrTempDateEmailAddress = [];
                
            //check user exist or not
            $userRecord = [];
            if(in_array($user['Email'], $arrAllEmailIDs )){
                $userRecord['id'] = $arrEmail[$user['Email']]['id'];
                $userRecord['email'] = $user['Email'];
            }else{
                $password = env("DEFAULT_PASSWORD", rand(1111111111,9999999999));
                $userRecord = [];
                $userRecord['id'] = '';
                $userRecord['email'] = $user['Email'];
                
                if(env("IS_SEND_MAIL_REGISTRAION")  === 'YES'){
                    try{
                        $response = $this->senduserCreationMail($user['Email'],  $password );
                        if(!$response){
                            
                            $arrTempDateEmailAddress['remark'] = 'email sending fail.';
                            $errorFlag = true;
                        }else{
                            
                        }    
                    }catch(Exception $e){
                        echo $e;
                    }
                    
                }   
                $userRecord['password'] = Hash::make($password);
                $arrTempDateEmailAddress['password'] = $password; 
            }

            //add or update new record in array for user 
            $userRecord['name'] = $user['Name'];
            $userRecord['phone_number'] = $user['Number'];
            $userRecord['email'] = $user['Email'];
            $userRecord['designation'] = $user['Designation'];
            $userRecord['is_admin'] = "No";
            $userRecord['can_make_call'] = in_array($user['Designation'], $arrCanMakeCall) ? "YES" : "NO";
            $userRecord['portal_access'] = in_array($user['Designation'], $arrNoPortalAccess) ? "NO" : "YES";
            $userRecord['level'] =  $this->getLevelByDesignation($user['Designation']);
            
            $arrTempDateEmailAddress['name'] = $userRecord['name'];
            $arrTempDateEmailAddress['phone_number'] = $userRecord['phone_number'];
            $arrTempDateEmailAddress['email'] = $userRecord['email'];
            $arrTempDateEmailAddress['is_admin'] =  "NO";
            $arrTempDateEmailAddress['designation'] =  $user['Designation'];
            $arrTempDateEmailAddress['can_make_call'] = $userRecord['can_make_call'];
            $arrTempDateEmailAddress['portal_access'] = $userRecord['portal_access'];
            $arrTempDateEmailAddress['level'] = $userRecord['level'];
            
            
            //Assign group name using aove 4 array 
            $arrTempDateEmailAddress['group1'] = $user['Group1'];
            if($user['Group1'] != ''){
                $userRecord['group1'] = (isset($arrBranchCode[trim($user['Group1'])])) ? $arrBranchCode[trim($user['Group1'])]: 0;
            }else{
                $userRecord['group1'] = 0;
            }
            
            $arrTempDateEmailAddress['group2'] = $user['Group2'];
            if($user['Group2'] != ''){
                $userRecord['group2'] = isset($arrRegoin[$user['Group2']]) ? $arrRegoin[$user['Group2']] : 0;
            }else{
                $userRecord['group2'] = 0;
            }

            $arrTempDateEmailAddress['group3'] = $user['Group3'];
            if($user['Group3'] != ''){
                $userRecord['group3'] = isset($arrZone[$user['Group3']]) ?  $arrZone[$user['Group3']] : 0;
            }else{
                $userRecord['group3'] = 0;
            }
            
            $arrTempDateEmailAddress['group4'] = $user['Group4'];
            if($user['Group4'] != ''){
                $userRecord['group4'] = isset($arrMegaZone[$user['Group4']]) ? $arrMegaZone[$user['Group4']] : 0;
            }else{
                $userRecord['group4'] = 0;
            }
            
            if($userRecord['email'] === ''){
                $arrTempDateEmailAddress['remark'] = 'email is not present.';
                $errorFlag = true;
            }

            if($userRecord['phone_number'] === ''){
                $arrTempDateEmailAddress['remark'] = 'phoneNumber is not present.';
                $errorFlag = true;
            }

            if($userRecord['designation'] === ''){
                $arrTempDateEmailAddress['remark'] = 'designation is not present.';
                $errorFlag = true;
            }
            
            if($userRecord['level'] === ''){
                $arrTempDateEmailAddress['remark'] = 'level is not present.';
                $errorFlag = true;
            }
            
            //check level Groups 
            $checkGroupsLveleWise = $this->checkgrouplevelWise($userRecord);
            if(!$checkGroupsLveleWise){
                $arrTempDateEmailAddress['remark'] = 'Invalid designation mapped for user.';
                $errorFlag = true;
            }
            //dd($user);
            //check email and phone number already exist or not 
            $usersCheckDuplicate = new User();
            if($userRecord['id'] != ''){
                $usersCheckDuplicate = $usersCheckDuplicate->where("id" , "<>", $userRecord['id'] );  
            }
            
            $usersCheckDuplicate = $usersCheckDuplicate->where(function($q) use ($user){
                $q->where('phone_number', $user['Number']) 
                ->orWhere('email', $user['Email']);
            } );
            
            
            $usersCheckDuplicate = $usersCheckDuplicate->get()->toArray();
            if(count($usersCheckDuplicate) > 0 ){
                $errorFlag = true;
                if($usersCheckDuplicate[0]['email']  === $userRecord['email']){
                    $arrTempDateEmailAddress['remark'] = 'Email is already exist.';
                }
                if($usersCheckDuplicate[0]['phone_number']  === $userRecord['phone_number']){
                    $arrTempDateEmailAddress['remark'] = 'Phone Number is already exist.';
                }
            }

            if($errorFlag){
                $errorOverAllFlag  = true;
                $arrTempDateEmailAddress['status'] = "error";
            }else{
                if($userRecord['id'] != ''){
                    try{
                        $userDetails = User::where('id', $userRecord['id'])->update($userRecord);
                        $arrTempDateEmailAddress['status'] = "success";
                        $arrTempDateEmailAddress['remark'] = "";
                    }catch(Exception $e){
                        $arrTempDateEmailAddress['status'] = "error";
                        $arrTempDateEmailAddress['remark'] = $e;;
                    }
                }else{
                    try{
                        $userDetails = User::create($userRecord);
                        $arrTempDateEmailAddress['status'] = "success";
                        $arrTempDateEmailAddress['remark'] = "";
                    }catch(Exception $e){
                        $arrTempDateEmailAddress['status'] = "error";
                        $arrTempDateEmailAddress['remark'] = $e;;
                    } 
                }
            }

            //check entry is present in user Log to update record 
            $userLog = UsersLog::where('email', $userRecord['email'])->get()->toArray();
            if(count($userLog) > 0 ){
                $updateLogDetails = UsersLog::where('id', $userLog[0]['id'])->update($arrTempDateEmailAddress);
            }else{
                $updateLogDetails = UsersLog::create($arrTempDateEmailAddress);
            }
            array_push($arrUpdateDateEmailAddress,$userRecord['email']);
        }
        

        //check if any email is exist or not and update delted at for that email 
        $arrRemovingEmailAdress = array_diff($arrAllEmailIDs,$arrUpdateDateEmailAddress);
        foreach($arrRemovingEmailAdress as $key => $emailAdress){
            if($arrEmail[$emailAdress]['is_admin'] !== 'YES'){
                $users = User::where('email', $emailAdress)->forcedelete();
                $users = UsersLog::where('email', $emailAdress)->forcedelete();
            }
        }
        
        return $errorOverAllFlag;
     }
    /**
     * addHierachyData stores and create hierarchy Data
     * 
     */

    private function addHierachyData($groupData){
        //return true;
        $hierachyData = [];
        forEach($groupData as $group){    
            if($group['Group4'] && $group['Group4'] != null) {
                $hierachyData[$group['Group4']][$group['Group3']][$group['Group2']][] =  $group['Group1'];
            }
        }
        try{
            forEach($hierachyData as $megaZoneMaster => $zoneDetails ){
                //check Mega exist Or not 
                $megaZoneid = $this->getIdByName(new MegaZoneMaster,'megazone_name', $megaZoneMaster);
                
                forEach($zoneDetails as $zoneName => $regoinDetails){
                    $zoneId = $this->getIdByName(new ZoneMaster,'zone_name', $zoneName ,'megazone_id',$megaZoneid);
                    
                    foreach($regoinDetails as $regoinName => $branchDetails){
                        $regoinId = $this->getIdByName(new RegionMaster,'region_name', $regoinName ,'zone_id',$zoneId);
                        
                        foreach($branchDetails as $key => $branchCode){
                            $branchId = $this->getIdByName(new BranchMaster,'branch_code', $branchCode ,'region_id',$regoinId);
                        }
                    }
                }
            }
            return true;
        }catch(Exception $e ){
            return false;
        }
    }

    /**
     * get Level
     */
     private function getLevelByDesignation($designation){
        //get all lvel
        $arrLevels =  explode(",",env("LEVELS"));
        
        for($i=0;$i<count($arrLevels); $i++){
            $arrLevelDesignation = explode(",", env($arrLevels[$i]));
            //print_r($arrLevelDesignation);
            if(in_array($designation,$arrLevelDesignation)){
                return $arrLevels[$i];
            }
        }
        
        return null;
        
     }

    /**
     * get Id by name bases on Model Name 
     */
    private function getIdByName($modelname,$key,$name, $parentKeyName = '',$parentId = 0 ){
        try{
            $details = $modelname::where($key, $name)->get()->toArray();
        
            if(count($details) == 0 ){
                if($name === ''){
                    return false;
                }
                $insertDetails = [];
                $insertDetails[$key] = trim($name);
                if($parentKeyName != '' && $parentId != 0){
                    $insertDetails[$parentKeyName] = $parentId;
                }
                $res = $modelname::create($insertDetails);
                $megaZoneId = $res->id;
            }else{
                $megaZoneId = $details[0]['id'];
            }
            return $megaZoneId;
        }catch(Exception $e){
            return 0;
        }    
    }

    private function senduserCreationMail($email, $password){
        
        
        try{
            $details = [
                'title' => 'You are registered with our system',
                'body' => "Hi, \n You are registered with this system. \n Your username is your email address and password is  $password"
            ];
            \Mail::to($email)->send(new \App\Mail\MyTestMail($details));
            return true;
        }catch(Exception $e){
            return false;
        }
        
     }

}
