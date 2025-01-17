<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Modules\Marketplace\Entities\Order;

class EnviarResumenOrder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    public $order, $empresa;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->empresa = view()->shared('empresa');
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            // Config::get('mail.mailers.smtp.username')
            from: new Address('ventas@next.net.pe', $this->empresa->name),
            subject: 'CONFIRMACIÓN DE PEDIDO #' . $this->order->purchase_number,
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
            view: 'emails.enviar-resumen-order',
        );
        // return new Content(
        //     markdown: 'emails.enviar-claimbook',
        // );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
