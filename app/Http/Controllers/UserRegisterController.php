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

        //Store File at specific location 
        
        //Processing Data Shhet 1 Which is 
        $groupData  = (new FastExcel)->sheet(1)->import('/var/www/html/ISPCalling/HIERACHY_IMPORT_DATA.xlsx');
        $hierachyData = [];
        forEach($groupData as $group){    
            if($group['group1'] && $group['group2'] && $group['group3'] && $group['group4']){
                $hierachyData[$group['group4']][$group['group3']][$group['group2']][] =  $group['group1'];
            }
        }
        
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

        dd($hierachyData);

        $users  = (new FastExcel)->sheet(2)->import('/var/www/html/ISPCalling/HIERACHY_IMPORT_DATA.xlsx');
        
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
