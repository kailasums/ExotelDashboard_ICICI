<?php 
namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class FirstSheetImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $data = [];
        for($i=1; $i<=50000;$i++){
            echo "processing Row $i";
            if($rows[$i][0] ){
                $data[$rows[$i][3]][$rows[$i][2]][$rows[$i][1]] = $rows[$i][0];
            }else{
               continue;
            }
        }

        dd($data);
        echo "==================================================<br/>";
    }
}