<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class sendEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $surat;
    public function __construct($surat)
    {
        $this->surat = $surat;
    }

    public function build()
    {
        return $this->view('Guest.emailkirim')
            ->subject('Pengiriman Surat')
            ->with([
                'nama_pengirim' => $this->surat['nama_pengirim'],
                'instansi_pengirim' => $this->surat['instansi_pengirim'],
                'no_telp_pengirim' => $this->surat['no_telp_pengirim'],
                'deskripsi_surat' => $this->surat['deskripsi_surat'],
                'nomer_surat_masuk' => $this->surat['nomer_surat_masuk']
            ]);
    }
}
