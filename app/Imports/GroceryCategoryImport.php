<?php

namespace App\Imports;

use App\GroceryCategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;

class GroceryCategoryImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $url = $row['image'];
        $contents = file_get_contents($url);
        $name = substr($url, strrpos($url, '/') + 1);
        $destinationPath = public_path('/images/upload/'). $name;
        file_put_contents($destinationPath, $contents);
        return new GroceryCategory([
            'name' => $row['name'],
            'status' => $row['status'],
            'image' => $name
        ]);
    }
}
