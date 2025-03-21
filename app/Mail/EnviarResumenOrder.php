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
        $order->load([
            'shipmenttype',
            'user',
            'entrega.sucursal.ubigeo',
            'client',
            'moneda',
            'direccion.ubigeo',
            'transaccion',
            'tvitems' => function ($query) {
                $query->with(['promocion', 'producto' => function ($subq) {
                    $subq->with(['unit', 'imagen', 'marca', 'category']);
                }, 'carshoopitems' => function ($q) {
                    $q->with(['itempromo', 'producto' => function ($db) {
                        $db->with(['unit', 'imagen', 'marca', 'category']);
                    }]);
                }]);
            },
            'trackings' => function ($query) {
                $query->with('trackingstate')->orderBy('date', 'asc');
            },
            'comprobante' => function ($query) {
                $query->with(['client', 'facturableitems', 'seriecomprobante.typecomprobante']);
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
            from: new Address(Config::get('mail.mailers.smtp.from'), $this->empresa->name),
            subject: 'CONFIRMACIÓN DEL PEDIDO #' . $this->order->purchase_number,
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
