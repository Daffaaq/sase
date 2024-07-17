<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LetterAccepted extends Mailable
{
    use Queueable, SerializesModels;

    public $letter;
    /**
     * Create a new message instance.
     */
    public function __construct($letter)
    {
        $this->letter = $letter;
    }

    public function build()
    {
        return $this->view('Guest.letterAccepted')
        ->with([
            'nama_pengirim' => $this->letter->nama_pengirim,
            'nomer_surat_masuk' => $this->letter->nomer_surat_masuk,
        ]);
    }
}
