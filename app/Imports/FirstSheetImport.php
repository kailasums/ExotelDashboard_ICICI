<?php 
namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class FirstSheetImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        
        $data = [];
        foreach ($rows as $row) 
        {
            $data[$row[3]][$row[2]][$row[1]] = $row[0];
            if(count($data)  > 10 ){
            break;
            }
        }

        var_dump($data);
        return ;
    }
}