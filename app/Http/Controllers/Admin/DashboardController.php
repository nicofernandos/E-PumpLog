<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\CssSelector\Node\FunctionNode;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'pageTitle' => 'Dashboard',
            'statistics' => [
                'bounce_rate' => '32.53%',
                'page_views' => 7682,
                'new_sessions' => 68.8,
                'avg_time' => '2m:35s',
            ]
        ];
        
        return view('admin.pages.dashboard', $data);
    }
}