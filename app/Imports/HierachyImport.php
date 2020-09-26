<?php

namespace App\Imports;
use App\MegaZoneMaster;
use App\ZoneMaster;
use App\ReligionMaster;
use App\BranchMaster;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\WithMappedCells;
    

class HierachyImport implements ToCollection
{
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    // public function mapping(): array
    // {
    //     return [
    //         'group1'  => 'A1',
    //         'group2'    => 'B2',
    //         'group3' => "C3",
    //         'group4' => "D4"
    //     ];
    // }
    
        public function collection(Collection $rows)
    {
        
        $data = [];
        for($i=1; $i<=10000;$i++){
            
            //$data[$rows[$i][3]][$rows[$i][2]][$rows[$i][1]] = $rows[$i][0];
                
            if($rows[$i][0] ){
                //dd("2");
                $data[$rows[$i][3]][$rows[$i][2]][$rows[$i][1]] = $rows[$i][0];
            }else{
               // dd("1");
            break;
            }
           // if($i == 13){exit();}
        }
        $i = 0;
        
        $arrMegaZone = array_keys($data);
        for($i = 0; $i < count($arrMegaZone);$i++){
            $megaZoneName = $arrMegaZone[$i];
            
            $record = MegaZoneMaster::where('mega_zone_name', $megaZoneName)->get()->toArray();
            if($i === 1){
                exit();
            }
            if(count($record) == 0){
                try{
                    $megaZoneMaster = new MegaZoneMaster;
                    $megaZoneMaster->mega_zone_name = $megaZoneName;
                    $a = $megaZoneMaster->save();
                    //$record = MegaZoneMaster::where('mega_zone_name', $megaZoneName)->get()->toArray();   
                }catch(Exception $e){   
                }
            }else{
                //$d  = $record[0];    
            }
        }
        
    }   
}