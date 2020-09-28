<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

// Use model For groups and user 
use App\MegaZoneMaster,
App\RegionMaster,
App\BranchMaster,
App\ZoneMaster,
App\FileUpload;


class UserRegisterController extends Controller
{
    /**
     * constructot to check path auth
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * User Register vai csv file 
     */
    public function index(){
        $fileData = FileUpload::all();
        return view('admin.registeruser', ["fileUpload" => $fileData]);
    }

    public function uploadFile(Request $request){
        ini_set('max_execution_time', 0);
        try{
            if ($request->isMethod('post')) {
                //check file is present or not
                if($request->hasFile('file')){

                    $fileDetails = $request->file('file');
                    $this->storeFile($fileDetails); // store file to specific location

                    //file Details store in Database 
                    $fileUpload = [];
                    $fileUpload['file_name'] = $fileDetails->getClientOriginalName();
                    $fileUpload['upload_status'] = 'completed';
                    $fileuploadStatus = FileUpload::create($fileUpload);

                    //check sheet is exixt or not 
                    $filePath = storage_path().'/app/public/'.$fileDetails->getClientOriginalName();
                    
                    //Processing Data Shhet 1 Which is 
                    $groupData  = (new FastExcel)->sheet(2)->import($filePath);

                    $hierachyData = [];
                    forEach($groupData as $group){    
                        if($group['Group4'] && $group['Group4'] != null) {
                            $hierachyData[$group['Group4']][$group['Group3']][$group['Group2']][] =  $group['Group1'];
                        }
                    }
                    
                    $arrMegaZone = $arrBranchCode = $arrZone = $arrRegoin = [];
                    forEach($hierachyData as $megaZoneMaster => $zoneDetails ){
                        //check Mega exist Or not 
                        $megaZoneid = $this->getIdByName(new MegaZoneMaster,'mega_zone_name', $megaZoneMaster);
                        
                        forEach($zoneDetails as $zoneName => $regoinDetails){
                            $zoneId = $this->getIdByName(new ZoneMaster,'zone_name', $zoneName ,'mega_zone_id',$megaZoneid);
                            
                            foreach($regoinDetails as $regoinName => $branchDetails){
                                $regoinId = $this->getIdByName(new RegionMaster,'region_name', $regoinName ,'zone_id',$zoneId);
                                
                                foreach($branchDetails as $key => $branchCode){
                                    $branchId = $this->getIdByName(new BranchMaster,'branch_code', $branchCode ,'region_id',$regoinId);
                                }
                            }
                        }
                    }
                    
                    $arrMegaZone = MegaZoneMaster::all()->pluck('id','mega_zone_name');
                    $arrZone = ZoneMaster::all()->pluck('id','zone_name');
                    $arrRegoin = RegionMaster::all()->pluck('id','region_name');
                    $arrBranchCode = BranchMaster::all()->pluck('id','branch_code');
                    
                    $arrAllEmailIDs = [];
                    //get all user users's email id to check exist or not 
                    $arrEmail = User::all()->keyBy('email')->toArray();
                    $arrAllEmailIDs = array_keys($arrEmail); 

                    $users  = (new FastExcel)->sheet(1)->import($filePath);
                    $arrUpdateDateEmailAddress = [];
                    foreach($users as  $key => $user ){
                        if($user['Email'] == ''){
                            continue;
                        }
    
                        $password = env("DEFAULT_PASSWORD", rand(1111111111,9999999999));
                        //check user exist or not
                        $userRecord = [];
                        if(in_array($user['Email'], $arrAllEmailIDs )){
                            $userRecord['id'] = $arrEmail[$user['Email']]['id'];
                            $userRecord['email'] = $user['Email'];
                        }else{
                            $userRecord = [];
                            $userRecord['id'] = '';
                            if(env("IS_SEND_MAIL_REGISTRAION")  === 'YES'){
                                //$this->senduserCreationMail($user['Email'],  $password );
                            }    
                        }
                        //add or update new record in array for user 
                        $userRecord['name'] = $user['Name'];
                        $userRecord['phone_number'] = $user['Mobile'];
                        $userRecord['email'] = $user['Email'];
                        $userRecord['designation'] = $user['Designation'];
                        $userRecord['password'] = Hash::make($password);

                        $userRecord['level'] =  $user['Levels'];
                        //Assign group name using aove 4 array 
                        
                        if($user['Group1'] != ''){
                            $userRecord['group1'] = $arrBranchCode[trim($user['Group1'])];
                        }else{
                            $userRecord['group1'] = 0;
                        }

                        if($user['Group2'] != ''){
                            $userRecord['group2'] = $arrRegoin[$user['Group2']];
                        }else{
                            $userRecord['group2'] = 0;
                        }


                        if($user['Group3'] != ''){
                            $userRecord['group3'] = $arrZone[$user['Group3']];
                        }else{
                            $userRecord['group3'] = 0;
                        }

                        if($user['Group4'] != ''){
                            $userRecord['group4'] = $arrMegaZone[$user['Group4']];
                        }else{
                            $userRecord['group4'] = 0;
                        }
                        
                        if($userRecord['id'] != ''){
                            try{
                                $userDetails = User::where('id', $userRecord['id'])->update($userRecord);
                            }catch(Exception $e){
                                continue;
                            }
                        }else{
                            try{
                                $userDetails = User::create($userRecord);
                            }catch(Exception $e){
                                continue;
                            } 
                        }       

                        array_push($arrUpdateDateEmailAddress,$userRecord['email']);
                    }


                    //check if any email is exist or not and update delted at for that email 
                    $arrRemovingEmailAdress = array_diff($arrAllEmailIDs,$arrUpdateDateEmailAddress);
                    foreach($arrRemovingEmailAdress as $key => $emailAdress){
                        if($arrEmail[$emailAdress]['is_admin'] !== 'YES'){
                            $users = User::where('email', $emailAdress)
                                    ->delete();
                        }
                    }

                    return redirect('/admin/register-user');
                }else{
                    return redirect('/admin/register-user');
                }
            }else{
                return redirect('/admin/register-user');
            }
        }catch(Exception $e){

        }
        
    }

    /**
     * send user registration mail 
     */

     private function senduserCreationMail($email, $password){
        $details = [
            'title' => 'You are registered with our system',
            'body' => "Hi, \n You are registered with this system. \n Your username is your email address and password is  $password"
        ];
        
        try{
            \Mail::to($email)->send(new \App\Mail\MyTestMail($details));
            return true;
        }catch(Exception $e){
            return true;
        }
        
     }
    /**
     * file Storage which is uploaded by user 
     */

    private function storeFile($fileDetails){
        try{
            // Store file to specific location 
            $filename = $fileDetails->getClientOriginalName();
            $fileLocation = Storage::disk('local')->putFileAs(
                'public/',
                $fileDetails,
                $filename
            ); // file stored at location storage/app/public/
            
            return true;
        }catch(Exception $e){
            return false;
        }
    }
    /**
     * get Id by name bases on Model Name 
     */
    private function getIdByName($modelname,$key,$name, $parentKeyName = '',$parentId = 0 ){
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
    }


    

    // public function  bulkregisterUser(){
    //   ini_set('max_execution_time', 0);
    //   $c = DB::table('users',1)->count();
    //   $insertData = [];
    //   $a = rand(10,100);
    //   for($j=$c; $j<$c+100000; $j++){
    //     $i = $j * $a;
    //     $i = $i . time();
    //         $insertData = [
    //             "username"=>"Krushna".$i,
    //                    "name"=>"Krush".$i,
    //                    "gender"=>"male",
    //                    "email"=>"batekrushna$i@gmail.com",
    //                    "password"=>Hash::make("batekrushna$i")
    //                   ];

    //                    User::create($insertData);
                       
    //   }
    // }


}
