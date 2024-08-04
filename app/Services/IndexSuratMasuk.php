<?php

namespace App\Services;

use App\Models\SifatIncomingLetter;
use App\Models\CategoryIncomingLetter;
use App\Models\CategoryOutgoingLetter;
use App\Models\CategoryArchiveIncomingLetter;
use App\Models\User;

class IndexSuratMasuk
{
    public function getIndexData()
    {
        $sifats = SifatIncomingLetter::all(); // Sesuaikan dengan model yang digunakan
        $categories = CategoryIncomingLetter::all(); // Sesuaikan dengan model yang digunakan
        $categoryOutgoingLetters = CategoryOutgoingLetter::all();
        $categoryArchiveLetters = CategoryArchiveIncomingLetter::all();
        $users = User::where('role', 'pegawai')->get();

        return compact('sifats', 'categories', 'categoryOutgoingLetters', 'categoryArchiveLetters', 'users');
    }
}
