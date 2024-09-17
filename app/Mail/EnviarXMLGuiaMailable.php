<?php

namespace App\Mail;

use App\Models\Guia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class EnviarXMLGuiaMailable extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $guia;

    public function __construct(Guia $guia)
    {
        $this->guia = $guia;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address(Config::get('mail.mailers.smtp.username'), $this->guia->sucursal->empresa->name),
            subject: 'RESUMEN GUÍA DE REMISIÓN ELECTRÓNICA ' . $this->guia->seriecompleta,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'emails.enviar-xml-guia',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        // $code_comprobante = $this->guia->seriecomprobante->typecomprobante->code;
        // $filename = $this->guia->seriecompleta;
        // $rutazip = 'xml/' . $code_comprobante . '/' . $this->guia->sucursal->empresa->document . '-' . $code_comprobante . '-' . $filename . '.zip';

        // return [
        //     Attachment::fromStorageDisk('local', $rutazip)
        //         ->as($filename . '.zip')
        //         ->withMime('application/zip'),
        // ];
    }
}
