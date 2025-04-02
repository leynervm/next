<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Mail\Mailables\Address;
use Modules\Marketplace\Entities\Order;


class TrackingOrderMailable extends Mailable
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
        $order->load([
            'shipmenttype',
            'user',
            'entrega.sucursal.ubigeo',
            'direccion.ubigeo',
            'trackings' => function ($query) {
                $query->with('trackingstate')->orderBy('date', 'asc');
            }
        ]);
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
            from: new Address(Config::get('mail.mailers.smtp.username'), $this->empresa->name),
            subject: 'ACTUALIZACIÓN DE ESTADO DEL PEDIDO #' . $this->order->purchase_number,
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
            view: 'emails.tracking-order-mailable',
        );
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
