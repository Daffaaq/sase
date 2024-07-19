<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use App\Models\OutgoingLetter;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OutgoingLetterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $outgoingLetter;

    /**
     * Create a new message instance.
     */
    public function __construct(OutgoingLetter $outgoingLetter)
    {
        $this->outgoingLetter = $outgoingLetter;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->view('Guest.outgoing_letter')
            ->with([
                'nomerSuratKeluar' => $this->outgoingLetter->nomer_surat_keluar,
                'namaPenerima' => $this->outgoingLetter->nama_penerima,
                'tanggalSuratKeluar' => $this->outgoingLetter->tanggal_surat_keluar,
                'keterangan' => $this->outgoingLetter->keterangan,
            'fileUrl' => asset('storage/' . str_replace('public/', '', $this->outgoingLetter->file)),
            ])
            ->subject('Surat Balasan');
    }
}
