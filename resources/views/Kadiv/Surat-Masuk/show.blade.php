@extends('Kadiv.new_layouts.main')

@section('container')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Surat Masuk</h6>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6>Nomer Surat:</h6>
                    <p>{{ $incomingLetter->nomer_surat_masuk }}</p>
                </div>
                <div class="col-md-6">
                    <h6>Nomer IDX:</h6>
                    <p>{{ $incomingLetter->nomer_surat_masuk_idx }}</p>
                </div>
                <div class="col-md-6">
                    <h6>Kategori Surat:</h6>
                    <p>{{ $incomingLetter->category->name_jenis_surat_masuk }}</p>
                </div>
                <div class="col-md-6">
                    <h6>Sifat Surat:</h6>
                    <p>{{ $incomingLetter->sifat->name_sifat }}</p>
                </div>
                <div class="col-md-6">
                    <h6>Tanggal Surat:</h6>
                    <p>{{ $incomingLetter->tanggal_surat_masuk }}</p>
                </div>
                <div class="col-md-6">
                    <h6>Status Surat:</h6>
                    <p>{{ $incomingLetter->status }}</p>
                </div>
                <div class="col-md-6">
                    <h6>Status Disposisi:</h6>
                    <p>{{ $incomingLetter->disposition_status }}</p>
                </div>
            </div>
            <a href="{{ route('surat-masuk-index-kadiv') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
@endsection
