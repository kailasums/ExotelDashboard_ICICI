<?php

namespace App\Imports;
use App\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $designation = str_replace(' ', '-', $row[8]);
        $password = '';  
        if(env('IS_SEND_MAIL_REGISTRAION') === 'YES'){
           $password = substr($row[0], 4 ).""(mt_rand(1000,9999));
           //Send mail Functionality based on Flag 
        }else{
            $password = mt_rand(10000000,99999999);
        }
        //get level by groups by by group 1 

        return new User([
            'name'     => $row[0],
            'email'    => $row[1],
            'phoneNumber'    => $row[2],
            'phoneNumber'    => $row[2],
            'password' => Hash::make($passowrd)
        ]);
    }
}