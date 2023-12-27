<?php

namespace App\Helpers\Facturacion;

use App\Helpers\FormatoPersonalizado;
use Carbon\Carbon;
use DOMDocument;
use Illuminate\Support\Facades\Storage;
use Modules\Facturacion\Entities\Comprobante;
use Modules\Ventas\Entities\Venta;

class createXML
{
   public function __construct()
   {
   }

   function comprobanteVentaXML($nombreXML, $emisor, $cliente, $comprobante)
   {

      //   dd($nombreXML, $emisor, $emisor->ubigeo, $cliente->name, $comprobante);

      $doc = new DOMDocument(); //clase que permite crear archivos, XML
      $doc->formatOutput = false;
      $doc->preserveWhiteSpace = false;
      $doc->encoding = 'utf-8';

      $codeDocumentClient = strlen(trim($cliente->document)) == 11 ? '6' : '1';
      //crear el texto o cadena del XML para generar el documento electronico
      $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
           <ext:UBLExtensions>
              <ext:UBLExtension>
                 <ext:ExtensionContent />
              </ext:UBLExtension>
           </ext:UBLExtensions>
           <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
           <cbc:CustomizationID schemeAgencyName="PE:SUNAT">2.0</cbc:CustomizationID>
           <cbc:ProfileID schemeName="Tipo de Operacion" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo17">0101</cbc:ProfileID>
           <cbc:ID>' . $comprobante->seriecompleta . '</cbc:ID>
           <cbc:IssueDate>' . Carbon::parse($comprobante->date)->format('Y-m-d') . '</cbc:IssueDate>
           <cbc:IssueTime>' . Carbon::parse($comprobante->date)->format('H:i:s') . '</cbc:IssueTime>
           <cbc:DueDate>' . Carbon::parse($comprobante->date)->format('Y-m-d') . '</cbc:DueDate>
           <cbc:InvoiceTypeCode listAgencyName="PE:SUNAT" listName="Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01" listID="0101" name="Tipo de Operacion" listSchemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo51">' . $comprobante->seriecomprobante->typecomprobante->code . '</cbc:InvoiceTypeCode>
           <cbc:Note languageLocaleID="1000"><![CDATA[' . $comprobante->leyenda . ']]></cbc:Note>';

      if ($comprobante->exonerado > 0) {
         $xml .= '<cbc:Note languageLocaleID="2001"><![CDATA[ BIENES TRANSFERIDOS EN LA AMAZONÍA REGIÓN SELVAPARA SER CONSUMIDOS EN LA MISMA ]]></cbc:Note>';
      }

      $xml .= '<cbc:DocumentCurrencyCode listID="ISO 4217 Alpha" listName="Currency" listAgencyName="United Nations Economic Commission for Europe">' . $comprobante->moneda->code . '</cbc:DocumentCurrencyCode>
           <cbc:LineCountNumeric>' . count($comprobante->facturableitems) . '</cbc:LineCountNumeric>
           <cac:Signature>
              <cbc:ID>' . $emisor->document . '</cbc:ID>
              <cbc:Note><![CDATA[' . $emisor->name . ']]></cbc:Note>
              <cac:SignatoryParty>
                 <cac:PartyIdentification>
                    <cbc:ID>' . $emisor->document . '</cbc:ID>
                 </cac:PartyIdentification>
                 <cac:PartyName>
                    <cbc:Name><![CDATA[' . $emisor->name . ']]></cbc:Name>
                 </cac:PartyName>
              </cac:SignatoryParty>
              <cac:DigitalSignatureAttachment>
                 <cac:ExternalReference>
                    <cbc:URI>#SignatureSP</cbc:URI>
                 </cac:ExternalReference>
              </cac:DigitalSignatureAttachment>
           </cac:Signature>
           <cac:AccountingSupplierParty>
              <cac:Party>
                 <cac:PartyIdentification>
                    <cbc:ID schemeID="6" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $emisor->document . '</cbc:ID>
                 </cac:PartyIdentification>
                 <cac:PartyName>
                    <cbc:Name><![CDATA[' . $emisor->name . ']]></cbc:Name>
                 </cac:PartyName>
                 <cac:PartyTaxScheme>
                     <cbc:RegistrationName><![CDATA[' . $emisor->name . ']]></cbc:RegistrationName>
                     <cbc:CompanyID schemeID="6" schemeName="SUNAT:Identificador de Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $emisor->document . '</cbc:CompanyID>
                     <cac:TaxScheme>
                        <cbc:ID schemeID="6" schemeName="SUNAT:Identificador de Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $emisor->document . '</cbc:ID>
                     </cac:TaxScheme>
                  </cac:PartyTaxScheme>
                 <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA[' . $emisor->name . ']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                       <cbc:ID schemeName="Ubigeos" schemeAgencyName="PE:INEI">' . $emisor->ubigeo->ubigeo_reniec . '</cbc:ID>
                       <cbc:AddressTypeCode listAgencyName="PE:SUNAT" listName="Establecimientos anexos">0000</cbc:AddressTypeCode>
                       <cbc:CityName>
                           <![CDATA[' . trim($comprobante->sucursal->ubigeo->region) . ']]>
                       </cbc:CityName>
                       <cbc:CountrySubentity>
                           <![CDATA[' . trim($comprobante->sucursal->ubigeo->provincia) . ']]>
                       </cbc:CountrySubentity>
                       <cbc:District>
                           <![CDATA[' . trim($comprobante->sucursal->ubigeo->distrito) . ']]>
                       </cbc:District>
                       <cac:AddressLine>
                          <cbc:Line><![CDATA[' . trim($comprobante->sucursal->direccion) . ']]></cbc:Line>
                       </cac:AddressLine>
                       <cac:Country>
                          <cbc:IdentificationCode listID="ISO 3166-1" listAgencyName="United Nations Economic Commission for Europe" listName="Country">PE</cbc:IdentificationCode>
                       </cac:Country>
                    </cac:RegistrationAddress>
                 </cac:PartyLegalEntity>
                 <cac:Contact>
                     <cbc:Name><![CDATA[ ]]></cbc:Name>
                  </cac:Contact>
              </cac:Party>
           </cac:AccountingSupplierParty>
           <cac:AccountingCustomerParty>
              <cac:Party>
                 <cac:PartyIdentification>
                    <cbc:ID schemeID="' . $codeDocumentClient . '" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $cliente->document . '</cbc:ID>
                 </cac:PartyIdentification>
                 <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA[' . $cliente->name . ']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                       <cac:AddressLine>
                          <cbc:Line><![CDATA[' . $comprobante->direccion . ']]></cbc:Line>
                       </cac:AddressLine>
                       <cac:Country>
                          <cbc:IdentificationCode listID="ISO 3166-1" listAgencyName="United Nations Economic Commission for Europe" listName="Country">PERU</cbc:IdentificationCode>
                       </cac:Country>
                    </cac:RegistrationAddress>
                 </cac:PartyLegalEntity>
              </cac:Party>
           </cac:AccountingCustomerParty>
           <cac:PaymentTerms>
               <cbc:ID>FormaPago</cbc:ID>
               <cbc:PaymentMeansID>' . $comprobante->typepayment->name . '</cbc:PaymentMeansID>';

      if ($comprobante->seriecomprobante->typecomprobante->code == '01' && $comprobante->typepayment->paycuotas) {
         $xml .= '<cbc:Amount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($comprobante->total - $comprobante->paymentactual) . '</cbc:Amount>';
      }

      $xml .= '</cac:PaymentTerms>';

      if ($comprobante->seriecomprobante->typecomprobante->code == '01' && $comprobante->facturable instanceof Venta) {
         if (count($comprobante->facturable->cuotas) > 0) {
            foreach ($comprobante->facturable->cuotas as $cuota) {
               $xml .= '<cac:PaymentTerms>
                        <cbc:ID>FormaPago</cbc:ID>
                        <cbc:PaymentMeansID>Cuota' . substr('000' . $cuota->cuota, -3) . '</cbc:PaymentMeansID>
                        <cbc:Amount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($cuota->amount) . '</cbc:Amount>
                        <cbc:PaymentDueDate>' . Carbon::parse($cuota->expiredate)->format('Y-m-d') . '</cbc:PaymentDueDate>
                     </cac:PaymentTerms>';
            }
         }
      }

      $xml .= '<cac:TaxTotal>
               <cbc:TaxAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($comprobante->igv) . '</cbc:TaxAmount>';

      if ($comprobante->gravado > 0) {
         $xml .= '<cac:TaxSubtotal>
                     <cbc:TaxableAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($comprobante->gravado) . '</cbc:TaxableAmount>
                     <cbc:TaxAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($comprobante->igv) . '</cbc:TaxAmount>
                     <cac:TaxCategory>
                     <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>
                        <cac:TaxScheme>
                           <cbc:ID>1000</cbc:ID>
                           <cbc:Name>IGV</cbc:Name>
                           <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                        </cac:TaxScheme>
                     </cac:TaxCategory>
                  </cac:TaxSubtotal>';
      }

      if ($comprobante->exonerado > 0) {
         $xml .= '<cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($comprobante->exonerado) . '</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="' . $comprobante->moneda->code . '">0.00</cbc:TaxAmount>
                    <cac:TaxCategory>
                       <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                       <cac:TaxScheme>
                          <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9997</cbc:ID>
                          <cbc:Name>EXO</cbc:Name>
                          <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                       </cac:TaxScheme>
                    </cac:TaxCategory>
                 </cac:TaxSubtotal>';
      }

      if ($comprobante->inafecto > 0) {
         $xml .= '<cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($comprobante->inafecto) . '</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="' . $comprobante->moneda->code . '">0.00</cbc:TaxAmount>
                    <cac:TaxCategory>
                       <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">O</cbc:ID>
                       <cac:TaxScheme>
                          <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9998</cbc:ID>
                          <cbc:Name>INA</cbc:Name>
                          <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                       </cac:TaxScheme>
                    </cac:TaxCategory>
                 </cac:TaxSubtotal>';
      }

      if ($comprobante->gratuito > 0) {
         $xml .= '<cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($comprobante->gratuito) . '</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($comprobante->igvgratuito) . '</cbc:TaxAmount>
                    <cac:TaxCategory>
                       <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">Z</cbc:ID>
                       <cac:TaxScheme>
                          <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9996</cbc:ID>
                          <cbc:Name>GRA</cbc:Name>
                          <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                       </cac:TaxScheme>
                    </cac:TaxCategory>
                 </cac:TaxSubtotal>';
      }

      $total_antes_de_impuestos = $comprobante->gravado + $comprobante->exonerado + $comprobante->inafecto;

      $xml .= '</cac:TaxTotal>
           <cac:LegalMonetaryTotal>
              <cbc:LineExtensionAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($total_antes_de_impuestos) . '</cbc:LineExtensionAmount>
              <cbc:TaxInclusiveAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($comprobante->total) . '</cbc:TaxInclusiveAmount>
              <cbc:PayableAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($comprobante->total) . '</cbc:PayableAmount>
           </cac:LegalMonetaryTotal>';

      foreach ($comprobante->facturableitems as $item) {

         $xml .= '<cac:InvoiceLine>
                  <cbc:ID>' . $item->item . '</cbc:ID>
                  <cbc:InvoicedQuantity unitCode="' . trim($item->unit) . '" unitCodeListAgencyName="United Nations Economic Commission for Europe" unitCodeListID="UN/ECE rec 20">' . FormatoPersonalizado::getValueDecimal($item->cantidad) . '</cbc:InvoicedQuantity>
                  <cbc:LineExtensionAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($item->subtotal) . '</cbc:LineExtensionAmount>
                  <cac:PricingReference>
                     <cac:AlternativeConditionPrice>
                        <cbc:PriceAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($item->codeafectacion == "1000" ? $item->igv + $item->price : $item->price) . '</cbc:PriceAmount>
                        <cbc:PriceTypeCode listName="Tipo de Precio" listAgencyName="PE:SUNAT" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">' . $item->codetypeprice . '</cbc:PriceTypeCode>
                     </cac:AlternativeConditionPrice>
                  </cac:PricingReference>
                  <cac:TaxTotal>
                     <cbc:TaxAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($item->subtotaligv) . '</cbc:TaxAmount>
                     <cac:TaxSubtotal>
                        <cbc:TaxableAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($item->subtotal) . '</cbc:TaxableAmount>
                        <cbc:TaxAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($item->subtotaligv) . '</cbc:TaxAmount>
                        <cac:TaxCategory>
                            <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">' . $item->abreviatureafectacion . '</cbc:ID>
                           <cbc:Percent>' . FormatoPersonalizado::getValueDecimal($item->percent) . '</cbc:Percent>
                           <cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT" listName="Afectacion del IGV" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">' . $item->afectacion . '</cbc:TaxExemptionReasonCode>
                           <cac:TaxScheme>
                              <cbc:ID schemeID="UN/ECE 5153" schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT">' . $item->codeafectacion . '</cbc:ID>
                              <cbc:Name>' . $item->nameafectacion . '</cbc:Name>
                              <cbc:TaxTypeCode>' . $item->typeafectacion . '</cbc:TaxTypeCode>
                           </cac:TaxScheme>
                        </cac:TaxCategory>
                     </cac:TaxSubtotal>
                  </cac:TaxTotal>
                  <cac:Item>
                     <cbc:Description><![CDATA[' . $item->descripcion . ']]></cbc:Description>
                     <cac:SellersItemIdentification>
                        <cbc:ID>' . $item->code . '</cbc:ID>
                     </cac:SellersItemIdentification>
                  </cac:Item>
                  <cac:Price>
                     <cbc:PriceAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($item->codeafectacion == "9996" ? 0 : $item->price) . '</cbc:PriceAmount>
                  </cac:Price>
               </cac:InvoiceLine>';
      }

      $xml .= "</Invoice>";

      $doc->loadXML($xml);
      $xmlString = $doc->saveXML();
      Storage::disk('local')->put($nombreXML . '.xml', $xmlString);

   }

   function notaCreditoXML($nombreXML, $emisor, $cliente, $comprobante, $motivo = null)
   {

      if (is_null($motivo)) {
         $motivo = response()->json([
            'code' => '01',
            'descripcion' => 'ANULACIÓN DE LA OPERACIÓN'
         ])->getData();
      }

      //   dd($nombreXML, $emisor, $emisor->ubigeo, $cliente->name, $comprobante);
      $comprobanteReferencia = Comprobante::where('seriecompleta', $comprobante->referencia)->first();
      $codeReferencia = $comprobanteReferencia->seriecomprobante->typecomprobante->code;

      $doc = new DOMDocument(); //clase que permite crear archivos, XML
      $doc->formatOutput = false;
      $doc->preserveWhiteSpace = false;
      $doc->encoding = 'utf-8';

      $codeDocumentClient = strlen(trim($cliente->document)) == 11 ? '6' : '1';
      //crear el texto o cadena del XML para generar el documento electronico
      $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
           <ext:UBLExtensions>
              <ext:UBLExtension>
                 <ext:ExtensionContent />
              </ext:UBLExtension>
           </ext:UBLExtensions>
           <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
           <cbc:CustomizationID schemeAgencyName="PE:SUNAT">2.0</cbc:CustomizationID>
           <cbc:ID>' . $comprobante->seriecompleta . '</cbc:ID>
           <cbc:IssueDate>' . Carbon::parse($comprobante->date)->format('Y-m-d') . '</cbc:IssueDate>
           <cbc:IssueTime>' . Carbon::parse($comprobante->date)->format('H:i:s') . '</cbc:IssueTime>
           <cbc:Note languageLocaleID="1000"><![CDATA[' . $comprobante->leyenda . ']]></cbc:Note>';

      if ($comprobante->exonerado > 0) {
         $xml .= '<cbc:Note languageLocaleID="2001"><![CDATA[ BIENES TRANSFERIDOS EN LA AMAZONÍA REGIÓN SELVAPARA SER CONSUMIDOS EN LA MISMA ]]></cbc:Note>';
      }

      $xml .= '<cbc:DocumentCurrencyCode listID="ISO 4217 Alpha" listName="Currency" listAgencyName="United Nations Economic Commission for Europe">' . $comprobante->moneda->code . '</cbc:DocumentCurrencyCode>
            <cac:DiscrepancyResponse>
               <cbc:ReferenceID>' . $comprobante->referencia . '</cbc:ReferenceID>
               <cbc:ResponseCode>' . $motivo->code . '</cbc:ResponseCode>
               <cbc:Description><![CDATA[' . $motivo->descripcion . ']]></cbc:Description>
            </cac:DiscrepancyResponse>
            <cac:BillingReference>
               <cac:InvoiceDocumentReference>
                  <cbc:ID>' . $comprobante->referencia . '</cbc:ID>
                  <cbc:DocumentTypeCode>' . $codeReferencia . '</cbc:DocumentTypeCode>
               </cac:InvoiceDocumentReference>
            </cac:BillingReference>
           <cac:Signature>
              <cbc:ID>' . $emisor->document . '</cbc:ID>
              <cbc:Note><![CDATA[' . $emisor->name . ']]></cbc:Note>
              <cac:SignatoryParty>
                 <cac:PartyIdentification>
                    <cbc:ID>' . $emisor->document . '</cbc:ID>
                 </cac:PartyIdentification>
                 <cac:PartyName>
                    <cbc:Name><![CDATA[' . $emisor->name . ']]></cbc:Name>
                 </cac:PartyName>
              </cac:SignatoryParty>
              <cac:DigitalSignatureAttachment>
                 <cac:ExternalReference>
                    <cbc:URI>#SignatureSP</cbc:URI>
                 </cac:ExternalReference>
              </cac:DigitalSignatureAttachment>
           </cac:Signature>
           <cac:AccountingSupplierParty>
              <cac:Party>
                 <cac:PartyIdentification>
                    <cbc:ID schemeID="6" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $emisor->document . '</cbc:ID>
                 </cac:PartyIdentification>
                 <cac:PartyName>
                    <cbc:Name><![CDATA[' . $emisor->name . ']]></cbc:Name>
                 </cac:PartyName>
                 <cac:PartyTaxScheme>
                     <cbc:RegistrationName><![CDATA[' . $emisor->name . ']]></cbc:RegistrationName>
                     <cbc:CompanyID schemeID="6" schemeName="SUNAT:Identificador de Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $emisor->document . '</cbc:CompanyID>
                     <cac:TaxScheme>
                        <cbc:ID schemeID="6" schemeName="SUNAT:Identificador de Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $emisor->document . '</cbc:ID>
                     </cac:TaxScheme>
                  </cac:PartyTaxScheme>
                 <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA[' . $emisor->name . ']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                       <cbc:ID schemeName="Ubigeos" schemeAgencyName="PE:INEI">' . $emisor->ubigeo->ubigeo_reniec . '</cbc:ID>
                       <cbc:AddressTypeCode listAgencyName="PE:SUNAT" listName="Establecimientos anexos">0000</cbc:AddressTypeCode>
                       <cbc:CityName>
                           <![CDATA[' . trim($comprobante->sucursal->ubigeo->region) . ']]>
                       </cbc:CityName>
                       <cbc:CountrySubentity>
                           <![CDATA[' . trim($comprobante->sucursal->ubigeo->provincia) . ']]>
                       </cbc:CountrySubentity>
                       <cbc:District>
                           <![CDATA[' . trim($comprobante->sucursal->ubigeo->distrito) . ']]>
                       </cbc:District>
                       <cac:AddressLine>
                          <cbc:Line><![CDATA[' . trim($comprobante->sucursal->direccion) . ']]></cbc:Line>
                       </cac:AddressLine>
                       <cac:Country>
                          <cbc:IdentificationCode listID="ISO 3166-1" listAgencyName="United Nations Economic Commission for Europe" listName="Country">PE</cbc:IdentificationCode>
                       </cac:Country>
                    </cac:RegistrationAddress>
                 </cac:PartyLegalEntity>
                 <cac:Contact>
                     <cbc:Name><![CDATA[ ]]></cbc:Name>
                  </cac:Contact>
              </cac:Party>
           </cac:AccountingSupplierParty>
           <cac:AccountingCustomerParty>
              <cac:Party>
                 <cac:PartyIdentification>
                    <cbc:ID schemeID="' . $codeDocumentClient . '" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $cliente->document . '</cbc:ID>
                 </cac:PartyIdentification>
                 <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA[' . $cliente->name . ']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                       <cac:AddressLine>
                          <cbc:Line><![CDATA[' . $comprobante->direccion . ']]></cbc:Line>
                       </cac:AddressLine>
                       <cac:Country>
                          <cbc:IdentificationCode listID="ISO 3166-1" listAgencyName="United Nations Economic Commission for Europe" listName="Country">PERU</cbc:IdentificationCode>
                       </cac:Country>
                    </cac:RegistrationAddress>
                 </cac:PartyLegalEntity>
              </cac:Party>
           </cac:AccountingCustomerParty>';

      if ($comprobante->typepayment->paycuotas) {
         $xml .= '<cac:PaymentTerms>
               <cbc:ID>FormaPago</cbc:ID>
               <cbc:PaymentMeansID>' . $comprobante->typepayment->name . '</cbc:PaymentMeansID>
               <cbc:Amount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($comprobante->total - $comprobante->paymentactual) . '</cbc:Amount>
            </cac:PaymentTerms>';
      }

      if (count($comprobante->cuotas) > 0) {
         foreach ($comprobante->cuotas as $cuota) {
            $xml .= '<cac:PaymentTerms>
                        <cbc:ID>FormaPago</cbc:ID>
                        <cbc:PaymentMeansID>Cuota' . substr('000' . $cuota->cuota, -3) . '</cbc:PaymentMeansID>
                        <cbc:Amount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($cuota->amount) . '</cbc:Amount>
                        <cbc:PaymentDueDate>' . Carbon::parse($cuota->expiredate)->format('Y-m-d') . '</cbc:PaymentDueDate>
                     </cac:PaymentTerms>';
         }
      }


      $xml .= '<cac:TaxTotal>
               <cbc:TaxAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($comprobante->igv) . '</cbc:TaxAmount>';

      if ($comprobante->gravado > 0) {
         $xml .= '<cac:TaxSubtotal>
                     <cbc:TaxableAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($comprobante->gravado) . '</cbc:TaxableAmount>
                     <cbc:TaxAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($comprobante->igv) . '</cbc:TaxAmount>
                     <cac:TaxCategory>
                     <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>
                        <cac:TaxScheme>
                           <cbc:ID>1000</cbc:ID>
                           <cbc:Name>IGV</cbc:Name>
                           <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                        </cac:TaxScheme>
                     </cac:TaxCategory>
                  </cac:TaxSubtotal>';
      }

      if ($comprobante->exonerado > 0) {
         $xml .= '<cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($comprobante->exonerado) . '</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="' . $comprobante->moneda->code . '">0.00</cbc:TaxAmount>
                    <cac:TaxCategory>
                       <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                       <cac:TaxScheme>
                          <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9997</cbc:ID>
                          <cbc:Name>EXO</cbc:Name>
                          <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                       </cac:TaxScheme>
                    </cac:TaxCategory>
                 </cac:TaxSubtotal>';
      }

      if ($comprobante->inafecto > 0) {
         $xml .= '<cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($comprobante->inafecto) . '</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="' . $comprobante->moneda->code . '">0.00</cbc:TaxAmount>
                    <cac:TaxCategory>
                       <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">O</cbc:ID>
                       <cac:TaxScheme>
                          <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9998</cbc:ID>
                          <cbc:Name>INA</cbc:Name>
                          <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                       </cac:TaxScheme>
                    </cac:TaxCategory>
                 </cac:TaxSubtotal>';
      }

      if ($comprobante->gratuito > 0) {
         $xml .= '<cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($comprobante->gratuito) . '</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($comprobante->igvgratuito) . '</cbc:TaxAmount>
                    <cac:TaxCategory>
                       <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">Z</cbc:ID>
                       <cac:TaxScheme>
                          <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9996</cbc:ID>
                          <cbc:Name>GRA</cbc:Name>
                          <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                       </cac:TaxScheme>
                    </cac:TaxCategory>
                 </cac:TaxSubtotal>';
      }

      $total_antes_de_impuestos = $comprobante->gravado + $comprobante->exonerado + $comprobante->inafecto;

      $xml .= '</cac:TaxTotal>
           <cac:LegalMonetaryTotal>
              <cbc:LineExtensionAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($total_antes_de_impuestos) . '</cbc:LineExtensionAmount>
              <cbc:TaxInclusiveAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($comprobante->total) . '</cbc:TaxInclusiveAmount>
              <cbc:PayableAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($comprobante->total) . '</cbc:PayableAmount>
           </cac:LegalMonetaryTotal>';

      foreach ($comprobante->facturableitems as $item) {

         $xml .= '<cac:CreditNoteLine>
                  <cbc:ID>' . $item->item . '</cbc:ID>
                  <cbc:CreditedQuantity unitCode="' . trim($item->unit) . '" unitCodeListAgencyName="United Nations Economic Commission for Europe" unitCodeListID="UN/ECE rec 20">' . FormatoPersonalizado::getValueDecimal($item->cantidad) . '</cbc:CreditedQuantity>
                  <cbc:LineExtensionAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($item->subtotal) . '</cbc:LineExtensionAmount>
                  <cac:PricingReference>
                     <cac:AlternativeConditionPrice>
                        <cbc:PriceAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($item->codeafectacion == "1000" ? $item->igv + $item->price : $item->price) . '</cbc:PriceAmount>
                        <cbc:PriceTypeCode listName="Tipo de Precio" listAgencyName="PE:SUNAT" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">' . $item->codetypeprice . '</cbc:PriceTypeCode>
                     </cac:AlternativeConditionPrice>
                  </cac:PricingReference>
                  <cac:TaxTotal>
                     <cbc:TaxAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($item->subtotaligv) . '</cbc:TaxAmount>
                     <cac:TaxSubtotal>
                        <cbc:TaxableAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($item->subtotal) . '</cbc:TaxableAmount>
                        <cbc:TaxAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($item->subtotaligv) . '</cbc:TaxAmount>
                        <cac:TaxCategory>
                            <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">' . $item->abreviatureafectacion . '</cbc:ID>
                           <cbc:Percent>' . FormatoPersonalizado::getValueDecimal($item->percent) . '</cbc:Percent>
                           <cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT" listName="Afectacion del IGV" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">' . $item->afectacion . '</cbc:TaxExemptionReasonCode>
                           <cac:TaxScheme>
                              <cbc:ID schemeID="UN/ECE 5153" schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT">' . $item->codeafectacion . '</cbc:ID>
                              <cbc:Name>' . $item->nameafectacion . '</cbc:Name>
                              <cbc:TaxTypeCode>' . $item->typeafectacion . '</cbc:TaxTypeCode>
                           </cac:TaxScheme>
                        </cac:TaxCategory>
                     </cac:TaxSubtotal>
                  </cac:TaxTotal>
                  <cac:Item>
                     <cbc:Description><![CDATA[' . $item->descripcion . ']]></cbc:Description>
                     <cac:SellersItemIdentification>
                        <cbc:ID>' . $item->code . '</cbc:ID>
                     </cac:SellersItemIdentification>
                  </cac:Item>
                  <cac:Price>
                     <cbc:PriceAmount currencyID="' . $comprobante->moneda->code . '">' . FormatoPersonalizado::getValueDecimal($item->codeafectacion == "9996" ? 0 : $item->price) . '</cbc:PriceAmount>
                  </cac:Price>
               </cac:CreditNoteLine>';
      }

      $xml .= "</CreditNote>";

      $doc->loadXML($xml);
      $xmlString = $doc->saveXML();
      Storage::disk('local')->put($nombreXML . '.xml', $xmlString);

   }


   function guiaRemisionXML($nombreXML, $emisor, $cliente, $guia)
   {
      // dd($nombreXML, $emisor, $cliente->name, $guia->guiaitems);
      $doc = new DOMDocument();
      $doc->formatOutput = false;
      $doc->preserveWhiteSpace = false;
      $doc->encoding = 'utf-8';

      $codeDocumentClient = strlen(trim($cliente->document)) == 11 ? '6' : '1';
      $codeDocumentDestinatario = strlen(trim($guia->documentdestinatario)) == 11 ? '6' : '1';

      $xml = '<?xml version="1.0" encoding="UTF-8"?>
         <DespatchAdvice xmlns="urn:oasis:names:specification:ubl:schema:xsd:DespatchAdvice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
            <ext:UBLExtensions>
               <ext:UBLExtension>
                  <ext:ExtensionContent />
               </ext:UBLExtension>
            </ext:UBLExtensions>
            <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
            <cbc:CustomizationID schemeAgencyName="PE:SUNAT">2.0</cbc:CustomizationID>
            <cbc:ID>' . $guia->seriecompleta . '</cbc:ID>
            <cbc:IssueDate>' . Carbon::parse($guia->date)->format('Y-m-d') . '</cbc:IssueDate>
            <cbc:IssueTime>' . Carbon::parse($guia->date)->format('H:i:s') . '</cbc:IssueTime>
            <cbc:DespatchAdviceTypeCode listAgencyName="PE:SUNAT" listName="Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">' . $guia->seriecomprobante->typecomprobante->code . '</cbc:DespatchAdviceTypeCode>';

      if (!empty($guia->note)) {
         $xml .= '<cbc:Note><![CDATA[' . $guia->note . ']]></cbc:Note>';
      }

      // ValidacionesGREv20221020_publicacion.xlsx
      if ($guia->comprobante) {
         $code = $guia->comprobante->seriecomprobante->typecomprobante->code;
         if ($code == "01" || $code == "03" || $code == "04" || $code == "09" || $code == "31") {
            $xml .= '<cac:AdditionalDocumentReference>
                  <cbc:ID>' . $guia->comprobante->seriecompleta . '</cbc:ID>
                  <cbc:DocumentTypeCode listAgencyName="PE:SUNAT" listName="Documento relacionado al transporte" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo61">' . $code . '</cbc:DocumentTypeCode>
                  <cbc:DocumentType>' . $guia->comprobante->seriecomprobante->typecomprobante->name . '</cbc:DocumentType>
                  <cac:IssuerParty>
                     <cac:PartyIdentification>
                     <cbc:ID schemeID="6" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $emisor->document . '</cbc:ID>
                     </cac:PartyIdentification>
                  </cac:IssuerParty>
               </cac:AdditionalDocumentReference>';
         }
      }

      $xml .= ' <cac:Signature>
               <cbc:ID>' . $emisor->document . '</cbc:ID>
               <cac:SignatoryParty>
                  <cac:PartyIdentification>
                     <cbc:ID>' . $emisor->document . '</cbc:ID>
                  </cac:PartyIdentification>
                  <cac:PartyName>
                     <cbc:Name><![CDATA[' . $emisor->name . ']]></cbc:Name>
                  </cac:PartyName>
               </cac:SignatoryParty>
               <cac:DigitalSignatureAttachment>
                  <cac:ExternalReference>
                     <cbc:URI>#SignatureSP</cbc:URI>
                  </cac:ExternalReference>
               </cac:DigitalSignatureAttachment>
            </cac:Signature>
            <cac:DespatchSupplierParty>
               <cbc:CustomerAssignedAccountID schemeID="6">' . $emisor->document . '</cbc:CustomerAssignedAccountID>
               <cac:Party>
                  <cac:PartyIdentification>
                     <cbc:ID schemeID="6" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $emisor->document . '</cbc:ID>
                  </cac:PartyIdentification>
                  <cac:PartyLegalEntity>
                     <cbc:RegistrationName><![CDATA[' . $emisor->name . ']]></cbc:RegistrationName>
                  </cac:PartyLegalEntity>
               </cac:Party>
            </cac:DespatchSupplierParty>
            <cac:DeliveryCustomerParty>
               <cac:Party>
                  <cac:PartyIdentification>
                     <cbc:ID schemeID="' . $codeDocumentDestinatario . '" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $guia->documentdestinatario . '</cbc:ID>
                  </cac:PartyIdentification>
                  <cac:PartyLegalEntity>
                     <cbc:RegistrationName><![CDATA[' . $guia->namedestinatario . ']]></cbc:RegistrationName>
                  </cac:PartyLegalEntity>
               </cac:Party>
            </cac:DeliveryCustomerParty>';

      if ($guia->motivotraslado->code == '03' || $guia->motivotraslado->code == '13') {
         $xml .= '<cac:BuyerCustomerParty>
               <cac:Party>
                  <cac:PartyIdentification>
                     <cbc:ID schemeID="' . $codeDocumentClient . '" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $cliente->document . '</cbc:ID>
                  </cac:PartyIdentification>
                  <cac:PartyLegalEntity>
                     <cbc:RegistrationName><![CDATA[' . $cliente->name . ']]></cbc:RegistrationName>
                  </cac:PartyLegalEntity>
               </cac:Party>
            </cac:BuyerCustomerParty>';
      }

      if ($guia->motivotraslado->code == '02' || $guia->motivotraslado->code == '07' || $guia->motivotraslado->code == '13') {
         $xml .= 'cac:SellerSupplierParty>
               <cac:Party>
                  <cac:PartyIdentification>
                     <cbc:ID schemeID="6" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">0000000000</cbc:ID>
                  </cac:PartyIdentification>
                  <cac:PartyLegalEntity>
                     <cbc:RegistrationName><![CDATA[NOMBRE RAZON SOCIAL DE PROVEEDOR DE COMPRA]]></cbc:RegistrationName>
                  </cac:PartyLegalEntity>
               </cac:Party>
            </cac:SellerSupplierParty>';
      }

      $xml .= '<cac:Shipment>
                  <cbc:ID>SUNAT_Envio</cbc:ID>
                  <cbc:HandlingCode listAgencyName="PE:SUNAT" listName="Motivo de traslado" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo20">' . $guia->motivotraslado->code . '</cbc:HandlingCode>
                  <cbc:HandlingInstructions><![CDATA[' . $guia->motivotraslado->name . ']]></cbc:HandlingInstructions>
                  <cbc:GrossWeightMeasure unitCode="' . $guia->unit . '">' . formatDecimalOrInteger($guia->peso, 3) . '</cbc:GrossWeightMeasure>
                  <cbc:TotalTransportHandlingUnitQuantity>' . $guia->packages . '</cbc:TotalTransportHandlingUnitQuantity>';

      if ($guia->indicadortransbordo == '1') {
         $xml .= '<cbc:SpecialInstructions>' . getIndicadorTransbProg()->code . '</cbc:SpecialInstructions>';
      }

      if ($guia->indicadorvehiculosml == '1') {
         $xml .= '<cbc:SpecialInstructions>' . getIndicadorVehiculoML()->code . '</cbc:SpecialInstructions>';
      }

      if ($guia->indicadorvehretorenvacios == '1') {
         $xml .= '<cbc:SpecialInstructions>' . getIndicadorRetornoVehEnvaVacio()->code . '</cbc:SpecialInstructions>';
      }

      if ($guia->indicadorvehretorvacio == '1') {
         $xml .= '<cbc:SpecialInstructions>' . getIndicadorRetornoVehVacio()->code . '</cbc:SpecialInstructions>';
      }

      if ($guia->indicadordamds == '1') {
         $xml .= '<cbc:SpecialInstructions>' . getIndicadorTotalDAMDS()->code . '</cbc:SpecialInstructions>';
      }

      if ($guia->indicadorconductor == '1') {
         $xml .= '<cbc:SpecialInstructions>' . getIndicadorRegistrarVehCondTransport()->code . '</cbc:SpecialInstructions>';
      }

      $xml .= '<cac:ShipmentStage>
                     <cbc:TransportModeCode listName="Modalidad de traslado" listAgencyName="PE:SUNAT" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo18">' . $guia->modalidadtransporte->code . '</cbc:TransportModeCode>
                     <cac:TransitPeriod>
                        <cbc:StartDate>' . Carbon::parse($guia->datetraslado)->format('Y-m-d') . '</cbc:StartDate>
                     </cac:TransitPeriod>';




      // 01: PUBLICO, 02:PRIVADO

      if ($guia->modalidadtransporte->code == '01') {
         if ($guia->indicadorvehiculosml == '0') {
            $xml .= '<cac:CarrierParty>
                     <cac:PartyIdentification>
                        <cbc:ID schemeID="6">' . $guia->ructransport . '</cbc:ID>
                     </cac:PartyIdentification>
                     <cac:PartyLegalEntity>
                        <cbc:RegistrationName><![CDATA[' . $guia->nametransport . ']]></cbc:RegistrationName>
                     </cac:PartyLegalEntity>
                  </cac:CarrierParty>';
         }
      } else {

         if (count($guia->transportvehiculos) > 0) {
            if (count($guia->transportvehiculos) == 1) {
               $vehiculoprincipal = $guia->transportvehiculos()->first();
            } else {
               $vehiculoprincipal = $guia->transportvehiculos()->principal()->first();
            }

            $xml .= '<cac:TransportMeans>
                        <cac:RoadTransport>
                           <cbc:LicensePlateID>' . $vehiculoprincipal->placa . '</cbc:LicensePlateID>
                        </cac:RoadTransport>
                     </cac:TransportMeans>';
         }

         if ($guia->indicadorvehiculosml == '0') {
            if (count($guia->transportdrivers) > 0) {
               foreach ($guia->transportdrivers as $driver) {
                  $codeDocumentDriver = strlen(trim($driver->document)) == 11 ? '6' : '1';
                  $jobTitle = $driver->isPrincipal() ? 'Principal' : 'Secundario';

                  $xml .= '<cac:DriverPerson>
                           <cbc:ID schemeID="' . $codeDocumentDriver . '" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $driver->document . '</cbc:ID>
                           <cbc:FirstName>' . $driver->name . '</cbc:FirstName>
                           <cbc:FamilyName>' . $driver->lastname . '</cbc:FamilyName>
                           <cbc:JobTitle>' . $jobTitle . '</cbc:JobTitle>
                           <cac:IdentityDocumentReference>
                              <cbc:ID>' . $driver->licencia . '</cbc:ID>
                           </cac:IdentityDocumentReference>
                        </cac:DriverPerson>';
               }
            }
         }
      }

      $xml .= '</cac:ShipmentStage>
               <cac:Delivery>
                  <cac:DeliveryAddress>
                     <cbc:ID schemeName="Ubigeos" schemeAgencyName="PE:INEI">' . $guia->ubigeodestino->ubigeo_inei . '</cbc:ID>';

      if ($guia->motivotraslado->code == '04') {
         $xml .= '<cbc:AddressTypeCode listID="' . $emisor->document . '" listAgencyName="PE:SUNAT" listName="Establecimientos anexos">' . $guia->anexodestino . '</cbc:AddressTypeCode>';
      }

      $xml .= '<cac:AddressLine>
                        <cbc:Line><![CDATA[' . $guia->direcciondestino . ']]></cbc:Line>
                     </cac:AddressLine>
                  </cac:DeliveryAddress>
                  <cac:Despatch>
                     <cac:DespatchAddress>
                        <cbc:ID schemeName="Ubigeos" schemeAgencyName="PE:INEI">' . $guia->ubigeoorigen->ubigeo_inei . '</cbc:ID>';

      if ($guia->motivotraslado->code == '04') {
         $xml .= '<cbc:AddressTypeCode listID="' . $emisor->document . '" listAgencyName="PE:SUNAT" listName="Establecimientos anexos">' . $guia->anexoorigen . '</cbc:AddressTypeCode>';
      }

      $xml .= '<cac:AddressLine>
                           <cbc:Line><![CDATA[' . $guia->direccionorigen . ']]></cbc:Line>
                        </cac:AddressLine>
                     </cac:DespatchAddress>
                  </cac:Despatch>
               </cac:Delivery>';

      if ($guia->indicadorvehiculosml == '0') {
         if ($guia->modalidadtransporte->code == '02') {
            if (count($guia->transportvehiculos) > 0) {
               $xml .= '<cac:TransportHandlingUnit>';

               foreach ($guia->transportvehiculos as $vehiculo) {
                  $xml .= '<cac:TransportEquipment>
                              <cbc:ID>' . $vehiculo->placa . '</cbc:ID>
                        </cac:TransportEquipment>';
               }

               $xml .= '</cac:TransportHandlingUnit>';
            }
         }
      } else {
         if (!empty(trim($guia->placavehiculo))) {
            $xml .= '<cac:TransportHandlingUnit>
                  <cac:TransportEquipment>
                     <cbc:ID>' . $guia->placavehiculo . '</cbc:ID>
                  </cac:TransportEquipment>
               </cac:TransportHandlingUnit>';
         }
      }

      $xml .= '</cac:Shipment>';

      $i = 1;
      foreach ($guia->tvitems as $item) {

         $xml .= '<cac:DespatchLine>
               <cbc:ID>' . $i . '</cbc:ID>
               <cbc:DeliveredQuantity unitCode="' . $item->producto->unit->name . '">' . formatDecimalOrInteger($item->cantidad) . '</cbc:DeliveredQuantity>
               <cac:OrderLineReference>
                  <cbc:LineID>' . $i . '</cbc:LineID>
               </cac:OrderLineReference>
               <cac:Item>
                  <cbc:Description><![CDATA[' . $item->producto->name . ']]></cbc:Description>
                  <cac:SellersItemIdentification>
                     <cbc:ID>' . $item->producto->code . '</cbc:ID>
                  </cac:SellersItemIdentification>
               </cac:Item>
            </cac:DespatchLine>';
         $i++;
      }

      $xml .= '</DespatchAdvice>';

      $doc->loadXML($xml);
      $xmlString = $doc->saveXML();
      Storage::disk('local')->put($nombreXML . '.xml', $xmlString);

   }
}
