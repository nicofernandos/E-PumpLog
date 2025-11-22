<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DailyReportController extends Controller
{
    public function index(){
        $data = [
            'title'=> 'E-PumpLog | Draft Harian Injeksi Pompa'
        ];
        return view('user.pages.dailyreport.index',compact('data'));
    }
}