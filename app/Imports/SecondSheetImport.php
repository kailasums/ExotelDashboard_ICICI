<?php 
namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SecondSheetImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // for($i = 0 ; $i < 100000;$i++){
        //     try{
        //         if($rows[$i] && $rows[$i][0]){
        //             echo $rows[$i][0] ."==".$rows[$i][1].$rows[$i][2] .$rows[$i][3];
        //         }else{
        //         break;
        //         }
        //     }catch(Exception $e){
        //        echo "in seconf sheet " . $i;
        //         continue;
        //     }
            
        //}
        $data = [];
        foreach ($rows as $row) 
        {
            array_push($data,$row);
            if(count($data)  > 10){
            break;
            }
        }
        var_dump($data);
        
    }

}