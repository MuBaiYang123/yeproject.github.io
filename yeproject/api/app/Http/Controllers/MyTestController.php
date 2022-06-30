<?php


namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Support\Facades\Request;

class MyTestController extends Controller
{
    public function myTest()
    {
        $arrayParams = [
          'user_id'=>2,
            'email'=>'m.yang@startiasoft.com',
            'age'=>20
        ];
        $blnResult = User::getUserInfo($arrayParams);
        return $blnResult;
    }

}
