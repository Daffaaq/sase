<?php

namespace App\Http\Controllers;

use App\Models\ArchiveIncomingLetter;
use App\Models\ArchiveOutgoingLetter;
use App\Models\DispotitionLetter;
use App\Models\IncomingLetter;
use App\Models\OutgoingLetter;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function indexSuperadmin()
    {
        return view('Superadmin.Dashboard.index');
    }
    public function indexKadiv()
    {
        $dataSuratMasuk = IncomingLetter::all()->count();
        $dataSuratKeluar = OutgoingLetter::all()->count();
        $dataSuratdisposisi = DispotitionLetter::all()->count();
        $dataSuratMasukArsip = ArchiveIncomingLetter::all()->count();
        $dataSuratKeluarArsip = ArchiveOutgoingLetter::all()->count();
        return view('Kadiv.Dashboard.index', compact('dataSuratMasuk', 'dataSuratKeluar', 'dataSuratdisposisi', 'dataSuratMasukArsip','dataSuratKeluarArsip'));
    }
    public function indexPegawai()
    {
        return view('Pegawai.Dashboard.index');
    }
}
