<?php
namespace App\Imports;
// use App\Imports\FirstSheetImport;
// use App\Imports\SecondSheetImport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UserHierachyImport implements WithMultipleSheets 
{
   
    public function sheets(): array
    {
        return [
            0 => new FirstSheetImport(),
            1 => new SecondSheetImport(),
        ];
    }
}