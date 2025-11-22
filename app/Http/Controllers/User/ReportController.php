<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $data = [
            'title'=> 'E-PumpLog | Laporan Harian Injeksi Pompa'
        ];
        return view('user.pages.report.index',compact('data'));
    }
}