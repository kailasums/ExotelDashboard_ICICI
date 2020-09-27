<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Hash;
//use Maatwebsite\Excel\Facades\Excel;
// use App\Imports\UserHierachyImport;
// use App\Imports\HierachyImport;

// Use model For groups and user 
use App\MegaZoneMaster,
App\RegionMaster,
App\BranchMaster,
App\ZoneMaster;

class UserRegisterController extends Controller
{
    /**
     * User Register vai csv file 
     */
    public function index(){
        return view('admin.registeruser');
    }

    public function uploadFile(Request $request){
        ini_set('max_execution_time', 0);
        try{
            if ($request->isMethod('post')) {
                //check file is present or not
                if($request->hasFile('file')){
                    //Store file to specific location 
                    $fileName = time().'_'.$req->file->getClientOriginalName();
                    $filePath = $req->file('file')->storeAs('uploads', $fileName, 'public');

                    //Processing Data Shhet 1 Which is 
                    $groupData  = (new FastExcel)->sheet(1)->import($fileDetails->store('temp'));
                    $hierachyData = [];
                    forEach($groupData as $group){    
                        if($group['group1'] && $group['group2'] && $group['group3'] && $group['group4']){
                            $hierachyData[$group['group4']][$group['group3']][$group['group2']][] =  $group['group1'];
                        }
                    }
                    $arrMegaZone = $arrBranchCode = $arrZone = $arrRegoin = [];
                    forEach($hierachyData as $megaZoneMaster => $zoneDetails ){
                        //check Mega exist Or not 
                        $megaZoneid = $this->getIdByName(new MegaZoneMaster,'mega_zone_name', $megaZoneMaster);
                        $arrMegaZone[trim($megaZoneMaster)] = $megaZoneid;

                        forEach($zoneDetails as $zoneName => $regoinDetails){
                            $zoneId = $this->getIdByName(new ZoneMaster,'zone_name', $zoneName ,'mega_zone_id',$megaZoneid);
                            $arrZone[trim($zoneName)] = $zoneId;

                            foreach($regoinDetails as $regoinName => $branchDetails){
                                $regoinId = $this->getIdByName(new RegionMaster,'region_name', $regoinName ,'zone_id',$zoneId);
                                $arrZone[trim($regoinName)] = $regoinId;

                                foreach($branchDetails as $key => $branchCode){
                                    $branchId = $this->getIdByName(new BranchMaster,'branch_code', $branchCode ,'region_id',$regoinId);
                                    $arrZone[trim($branchCode)] =$branchId;
                                }
                            }
                        }
                    }

                    //get all user users's email id to check exist or not 
                    $arrEmail = User::all()->keyBy('email')->toArray();
                    $arrAllEmailIDs = array_keys($arrEmail); 
                    
                    $users  = (new FastExcel)->sheet(2)->import($fileDetails->store('temp'));
                    $arrUpdateDateEmailAddress = [];
                    foreach($users  as  $key => $user ){
                        //get user By email Address 

                        //check user exist or not 
                        if(in_array($arrAllEmailIDs, $user['email'])){
                            $userRecord = $arrEmail[$user['email']]; 
                        }else{
                            $userRecord = [];
                        }
                        //add or update new record in array for user 
                        $userRecord['name'] = $user['name'];
                        $userRecord['phone_number'] = $user['phone_number'];
                        $userRecord['email'] = $user['email'];
                        $userRecord['designation'] = $user['designation'];

                        //Assign group name using aove 4 array 
                        if($user['group1'] != ''){
                            $userRecord['group1'] = $arrBranchCode[$user['group1']];
                        }else{
                            $userRecord['group1'] = 0;
                        }

                        if($user['group2'] != ''){
                            $userRecord['group2'] = $arrRegoin[$user['group2']];
                        }else{
                            $userRecord['group2'] = 0;
                        }


                        if($user['group3'] != ''){
                            $userRecord['group3'] = $arrZone[$user['group3']];
                        }else{
                            $userRecord['group3'] = 0;
                        }

                        if($user['group4'] != ''){
                            $userRecord['group4'] = $arrMegaZone[$user['group4']];
                        }else{
                            $userRecord['group4'] = 0;
                        }

                        if($userRecord[id] != ''){
                            $userDetails = User::where('id', $userRecord['id'])->update($userRecord) ;
                        }else{
                            $userDetails = User::create($userRecord);
                        }       
                        array_push($arrUpdateDateEmailAddress,$userRecord['email']);
                    }


                    //check if any email is exist or not and update delted at for that email 
                    $arrRemovingEmailAdres = array_diff(arrAllEmailIDs,$arrUpdateDateEmailAddress);
                    foreach($arrRemovingEmailAdres as $key =>$emailAdress){

                    }
                }else{
                    redirect()->route('admin/register-user');
                }

            }else{
                redirect()->route('admin/register-user');
            }
        }catch(Exception $e){

        }
        
    }


    
    /**
     * get Id by name bases on Model Name 
     */
    private function getIdByName($modelname,$key,$name, $parentKeyName = '',$parentId = 0 ){
        $details = $modelname::where($key, $name)->get()->toArray();
        
        if(count($details) == 0 ){
            $insertDetails = [];
            $insertDetails[$key] = $name;
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
