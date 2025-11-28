<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $data = [
            'title'=> 'E-PumpLog | Laporan Harian Injeksi Pompa',
            'subtitle'=> 'Laporan Harian Injeksi Pompa Injeksi dan Engine Pompa',
        ];
        return view('user.pages.report.index',compact('data'));
    }
}