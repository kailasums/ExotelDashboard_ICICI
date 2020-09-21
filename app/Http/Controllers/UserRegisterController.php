<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserRegisterController extends Controller
{
    /**
     * User Register vai csv file 
     */
    public function index(){
        return view('admin.registeruser');
    }

    public function uploadFile(){

        // if ($request->input('submit') != null ){

        //     $file = $request->file('file');
      
        //     // File Details 
        //     $filename = $file->getClientOriginalName();
        //     $extension = $file->getClientOriginalExtension();
        //     $tempPath = $file->getRealPath();
        //     $fileSize = $file->getSize();
        //     $mimeType = $file->getMimeType();
      
        //     // Valid File Extensions
        //     $valid_extension = array("csv");
      
        //     // 2MB in Bytes
        //     $maxFileSize = 2097152; 
      
        //     // Check file extension
        //     if(in_array(strtolower($extension),$valid_extension)){
      
        //       // Check file size
        //       if($fileSize <= $maxFileSize){
      
        //         // File upload location
        //         $location = 'uploads';
      
        //         // Upload file
        //         $file->move($location,$filename);
      
        //         // Import CSV to Database
        //         $filepath = public_path($location."/".$filename);
      
        //         // Reading file
        //         $file = fopen($filepath,"r");
      
        //         $importData_arr = array();
        //         $i = 0;
      
        //         while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
        //            $num = count($filedata );
                   
        //            // Skip first row (Remove below comment if you want to skip the first row)
        //            /*if($i == 0){
        //               $i++;
        //               continue; 
        //            }*/
        //            for ($c=0; $c < $num; $c++) {
        //               $importData_arr[$i][] = $filedata [$c];
        //            }
        //            $i++;
        //         }
        //         fclose($file);
      
        //         // Insert to MySQL database
        //         foreach($importData_arr as $importData){
      
        //           $insertData = array(
        //              "username"=>$importData[1],
        //              "name"=>$importData[2],
        //              "gender"=>$importData[3],
        //              "email"=>$importData[4]);
        //              User::insertData($insertData);
      
        //         }
      
        //         Session::flash('message','Import Successful.');
        //       }else{
        //         Session::flash('message','File too large. File must be less than 2MB.');
        //       }
      
        //     }else{
        //        Session::flash('message','Invalid File Extension.');
        //     }
      
        //   }
          
        //   // Redirect to index
        //   return redirect('/admin/register-user');
        // }
        
    }

    public function  bulkregisterUser(){
      ini_set('max_execution_time', 0);
      $c = DB::table('users',1)->count();
      $insertData = [];
      $a = rand(10,100);
      for($j=$c; $j<$c+100000; $j++){
        $i = $j * $a;
        $i = $i . time();
            $insertData = [
                "username"=>"Krushna".$i,
                       "name"=>"Krush".$i,
                       "gender"=>"male",
                       "email"=>"batekrushna$i@gmail.com",
                       "password"=>Hash::make("batekrushna$i")
                      ];

                       User::create($insertData);
                       
      }
    }


}
