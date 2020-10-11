<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Jobs\SendFileToProcess;
use App\User;
use Rap2hpoutre\FastExcel\FastExcel;
use Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

// Use model For groups and user 
use App\MegaZoneMaster,
App\RegionMaster,
App\BranchMaster,
App\ZoneMaster,
App\FileUpload,
App\UsersLog;


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
        $fileData = FileUpload::orderBy('created_at', 'desc')
                                ->limit(3)->get()->toArray();
        return view('admin.registeruser', ["fileUploadRecord" => $fileData]);
    }

    public function uploadFile(Request $request){
        ini_set('max_execution_time', 0);
        try{
            if ($request->isMethod('post')) {
                if($request->hasFile('file')){
                    $fileDetails = $request->file('file');
                    $fileExtension = $fileDetails->getClientOriginalExtension();
                    
                    if(str_replace(".xls","",str_replace(".xlsx","",$fileDetails->getClientOriginalName())) !== env("USER_UPLOAD_FILENAME") ){
                        return redirect('/import-users')->with('error',trans('uploadfile.filenNameNotMatch'));
                    }
                    
                    $processingFileCount = FileUpload::whereIn('upload_status', ['pending', 'processing'])->get()->toArray();
                    if(count($processingFileCount) > 0 ){
                        return redirect('/import-users')->with('error',trans('uploadfile.filePendingToUpload'));
                    }
                    
                    $extensions = explode(",",env("FILE_EXTENSION"));
                    $result = array($fileDetails->getClientOriginalExtension());
                    
                    
                    if(!in_array($result[0],$extensions)){
                        return redirect('/import-users')->with('error',trans('uploadfile.fileFormatNotMatch'));
                    }  //File format check 
                    //UsersLog::truncate();
                    $this->storeFile($fileDetails); // store file to specific location

                    $fileUpload = [];
                    $fileUpload['file_name'] = $fileDetails->getClientOriginalName();
                    $fileUpload['upload_status'] = 'pending';
                    $fileuploadStatus = FileUpload::create($fileUpload);

                    dispatch(new SendFileToProcess($fileuploadStatus));

                    return redirect('/import-users')->with('success',trans('uploadfile.success'));
                }else{
                    return redirect('/import-users')->with('error',trans('uploadfile.fileRequire'));
                }
            }else{
                return redirect('/import-users')->with('error',"no data Present.");;
            }
        }catch(Exception $e){
            return redirect('/import-users')->with('error',"something went wrong.$e");
        }
    }



    public function exportLog(){
        try{
            $sheets = UsersLog::select(['name','email', 'phone_number','designation','group1','group2','group3','group4','designation','status','remark'])->get();
        
            $arrImportData = [];
            if(count($sheets) > 0 ){
                for($i=0; $i< count($sheets); $i++){
                    $tempData = [];
                    $tempData['Name']= $sheets[$i]['name'];
                    $tempData['Email']= $sheets[$i]['email'];
                    $tempData['Number'] = $sheets[$i]['phone_number'];
                    $tempData['Group1'] = $sheets[$i]['group1'];
                    $tempData['Group2'] = $sheets[$i]['group2'];
                    $tempData['Group3'] = $sheets[$i]['group3'];
                    $tempData['Group4'] = $sheets[$i]['group4'];
                    $tempData['Designation'] = $sheets[$i]['designation'];
                    $tempData['Status'] = $sheets[$i]['status'];
                    $tempData['Remark'] = $sheets[$i]['remark'];
                    array_push($arrImportData,$tempData);
                }
            }

            (new FastExcel($arrImportData))->export('userLog.xlsx');
            return redirect(url('userLog.xlsx'));
        }catch(Exception $e){
            return redirect(url('/home'))->with("error", "Please try again.");
        }
         
    }


    public function exportPassword(){
        try{
            if(env("DOWNLOADPASSWORDLINK") === "YES"){
                $sheets = UsersLog::select(['email','phone_number','password'])->get()->toArray();
                $arrImportData = [];
                if(count($sheets) > 0 ){
                    for($i=0; $i< count($sheets); $i++){
                        $tempData = [];
                        $tempData['Email']= $sheets[$i]['email'];
                        $tempData['Number'] = $sheets[$i]['phone_number'];
                        $tempData['password'] = $sheets[$i]['password'];
                        array_push($arrImportData,$tempData);
                    }
                }
                (new FastExcel($arrImportData))->export('userPassword.xlsx');
                return redirect(url('userPassword.xlsx'));
            }else{
                return redirect(url('/home'));
            }
        }catch(Exception $e){
            return redirect(url('/home'))->with("error", "Please try again.");
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
                env("IMPORTFILESTORAGENAME",$filename)//
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


    public function backCallLogs(){
        $arrUsers = User::get()->toArray();
        for($i=0;$i<500; $i++){

        
        $rand = rand(2,4);
        $status = ['Failed','Completed','Busy','No Answer'];
        $callLogs=[];
        $callLogs['from_number'] = '111111111';
        $callLogs['to_number'] = $arrUsers[$rand]['phone_number'];
        $callLogs['call_duration'] = "".rand(100,300);
        $callLogs['call_status'] = $status[3];
        $callLogs['call_direction'] = "Incoming";
        $callLogs['call_recording_link'] = "-";
        $callLogs['call_sid'] = "123456780scxrfdxgfdhfdhgdfhfdgdf";
        $callLogs['agent_name'] = $arrUsers[$rand]['name'];
        $callLogs['agent_phone_number'] = $arrUsers[$rand]['phone_number'];
        $callLogs['user_id'] = $arrUsers[$rand]['id'];
        $callLogs['group1'] = $arrUsers[$rand]['group1'];
        $callLogs['group2'] = $arrUsers[$rand]['group2'];
        $callLogs['group3'] = $arrUsers[$rand]['group3'];
        $callLogs['group4'] = $arrUsers[$rand]['group4'];

        \App\CallRecording::create($callLogs);
        }
        
        for($i=0;$i<750; $i++){

        
            $rand = rand(2,4);
            $status = ['Failed','Completed','Busy','No Answer'];
            $callLogs=[];
            $callLogs['from_number'] = $arrUsers[$rand]['phone_number'];
            $callLogs['to_number'] = '111111111';
            $callLogs['call_duration'] = "".rand(100,300);
            $callLogs['call_status'] = $status[3];
            $callLogs['call_direction'] = "Outgoing";
            $callLogs['call_recording_link'] = "-";
            $callLogs['call_sid'] = "123456780scxrfdxgfdhfdhgdfhfdgdf";
            $callLogs['agent_name'] = $arrUsers[$rand]['name'];
            $callLogs['agent_phone_number'] = $arrUsers[$rand]['phone_number'];
            $callLogs['user_id'] = $arrUsers[$rand]['id'];
            $callLogs['group1'] = $arrUsers[$rand]['group1'];
            $callLogs['group2'] = $arrUsers[$rand]['group2'];
            $callLogs['group3'] = $arrUsers[$rand]['group3'];
            $callLogs['group4'] = $arrUsers[$rand]['group4'];
    
            \App\CallRecording::create($callLogs);
            }

        
        return true;
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
