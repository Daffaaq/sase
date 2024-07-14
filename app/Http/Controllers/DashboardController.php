<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function indexSuperadmin()
    {
        return view('Superadmin.Dashboard.index');
    }
    public function indexKadiv()
    {
        return view('Kadiv.Dashboard.index');
    }
    public function indexPegawai()
    {
        return view('Pegawai.Dashboard.index');
    }
}
