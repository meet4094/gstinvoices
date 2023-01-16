
<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Sell_invoicespdf extends CI_Controller
{

    function Invoices_pdf($id)
    {
        require('fpdf/fpdf.php');
        
        $iQry = "SELECT sell_invoices.id,sell_invoices.user_id,sell_invoices.invoices_date,sell_invoices.invoices_no,sell_invoices.customer_id,sell_invoices.gst,sell_invoices.sub_total,sell_invoices.product_sgst,sell_invoices.product_cgst,sell_invoices.round_off,sell_invoices.invoices_total,sell_invoices.is_del,customer.trade_name as customertradename,customer.address as customeraddress,customer.gst_number as customergstnumber,customer.place_of_supply as customerplaceofsupply FROM sell_invoices JOIN customer ON (customer.id = sell_invoices.customer_id) AND sell_invoices.id = {$id}";
        $sellinvoiceDetailes = $this->model_api->execute_query($iQry);

        $userId = $sellinvoiceDetailes['data'][0]['user_id'];
        $sellInvoiceId = $sellinvoiceDetailes['data'][0]['id'];
        $customertradename = $sellinvoiceDetailes['data'][0]['customertradename'];
        $customeraddress = $sellinvoiceDetailes['data'][0]['customeraddress'];
        $customerplaceofsupply = $sellinvoiceDetailes['data'][0]['customerplaceofsupply'];
        $customergstnumber = $sellinvoiceDetailes['data'][0]['customergstnumber'];
        $invoicesdate = $sellinvoiceDetailes['data'][0]['invoices_date'];
        $invoicesno = $sellinvoiceDetailes['data'][0]['invoices_no'];
        $productgst = $sellinvoiceDetailes['data'][0]['gst'];
        $subtotal = $sellinvoiceDetailes['data'][0]['sub_total'];
        $productcgst = $sellinvoiceDetailes['data'][0]['product_cgst'];
        $productsgst = $sellinvoiceDetailes['data'][0]['product_sgst'];
        $roundoff = $sellinvoiceDetailes['data'][0]['round_off'];
        $invoicestotal = $sellinvoiceDetailes['data'][0]['invoices_total'];

        $userDetailes = $this->model_api->get("sh_users", array("uid,trade_name,gst_number,mobile_number,address,is_del"), array("and" => array("uid" => $userId)));
        $usertradname = $userDetailes['data'][0]['trade_name'];
        $useraddress = $userDetailes['data'][0]['address'];
        $usermobilenumber = $userDetailes['data'][0]['mobile_number'];
        $usergstnumberer = $userDetailes['data'][0]['gst_number'];

        $iQry = "SELECT sell_product_detailes.id,sell_product_detailes.product_id,sell_product_detailes.sell_invoice_id,sell_product_detailes.product_id,sell_product_detailes.product_hsn_sac,sell_product_detailes.product_quantity,sell_product_detailes.product_rate,sell_product_detailes.total_amount,sell_product_detailes.is_del,product_detailes.product_name as productname FROM sell_product_detailes JOIN product_detailes ON (product_detailes.id = sell_product_detailes.product_id) AND sell_product_detailes.sell_invoice_id = {$sellInvoiceId}";
        $productDetailes = $this->model_api->execute_query($iQry);

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 26);
        $pdf->Cell(40, 12, $usertradname, 0, 1);

        $pdf->SetFont('', '', 12);
        $pdf->Cell(40, 7, $useraddress, 0, 1);
        $pdf->Cell(40, 7, 'Mo. +91-' . $usermobilenumber, 0, 1);

        $pdf->SetFont('', 'B', 20);
        $pdf->Cell(0, 10, 'INVOICES', 0, 1, 'R');
        $pdf->Cell(0, 1, '', 1, 0,);
        $pdf->Cell(0, 5, '', 0, 1,);

        $pdf->SetFont('Arial', 'I', 20);
        $pdf->Cell(0, 10, 'Invoice to : ', 0, 0);

        $pdf->SetFont('Arial', '', 13);
        $pdf->Cell(0, 7, 'Date : ' . $invoicesdate, 0, 1, 'R');
        $pdf->Cell(0, 7, 'Invoice No : ' . $invoicesno, 0, 1, 'R');

        $pdf->SetFont('', 'B', 18);
        $pdf->Cell(0, 10, $customertradename, 0, 1);

        $pdf->SetFont('', '', 13);
        $pdf->Cell(50, 10, $customeraddress, 0, 1);

        $pdf->SetFont('', 'B', 11);
        $pdf->Cell(0, 8, 'Place of Supply : ' . $customerplaceofsupply, 0, 1);
        $pdf->Cell(0, 8, 'GSTIN No : ' . $customergstnumber, 0, 1);

        $pdf->Cell(0, 7, '', 0, 1,);

        $pdf->SetFont('', 'B', 13);
        $pdf->Cell(15, 10, 'No.', 1, 0, 'C');
        $pdf->Cell(55, 10, 'Details', 1, 0, 'C');
        $pdf->Cell(25, 10, 'HSN/SAC', 1, 0, 'C');
        $pdf->Cell(25, 10, 'Qty', 1, 0, 'C');
        $pdf->Cell(25, 10, 'Rate', 1, 0, 'C');
        $pdf->Cell(25, 10, 'GST%', 1, 0, 'C');
        $pdf->Cell(25, 10, 'Amount', 1, 1, 'C');
        $pdf->Cell(0, 3, '', 0, 1,);

        foreach ($productDetailes['data'] as $key => $productDetaile) {
            $productname = $productDetaile['productname'];
            $producthsncode = $productDetaile['product_hsn_sac'];
            $productqty = $productDetaile['product_quantity'];
            $productrate = $productDetaile['product_rate'];
            $producttotalamount = $productDetaile['total_amount'];
            $pdf->SetFont('', '', 11);
            $pdf->Cell(15, 10, $key, 0, 0, 'C');
            $pdf->Cell(55, 10, $productname, 0, 0, 'C');
            $pdf->Cell(25, 10, $producthsncode, 0, 0, 'C');
            $pdf->Cell(25, 10, $productqty, 0, 0, 'C');
            $pdf->Cell(25, 10, $productrate, 0, 0, 'C');
            $pdf->Cell(25, 10, $productgst, 0, 0, 'C');
            $pdf->Cell(25, 10, $producttotalamount, 0, 1, 'C');
        }
        $pdf->Cell(0, 20, '', 0, 1);
        $pdf->Cell(0, 0, '', 1, 1,);
        $pdf->Cell(0, 5, '', 0, 1);

        $pdf->SetFont('', 'B', 11);
        $pdf->Cell(0, 15, 'GSTIN NO : ' . $usergstnumberer, 0, 0);

        $pdf->Cell(0, 8, 'Subtotal : ' . $subtotal, 0, 1, 'R');
        $pdf->Cell(0, 8, 'CGST : ' . $productcgst, 0, 1, 'R');
        $pdf->Cell(0, 8, 'SGST : ' . $productsgst, 0, 1, 'R');
        $pdf->Cell(0, 8, 'Round Off : ' . $roundoff, 0, 1, 'R');
        $pdf->Cell(0, 8, 'Total : ' . $invoicestotal, 0, 1, 'R');

        $pdf->Cell(0, 10, '', 0, 1);
        $pdf->SetFont('', 'B', 13);
        $pdf->Cell(0, 10, 'Terms & Conditions', 0, 1);

        $pdf->SetFont('', '', 11);
        $pdf->Cell(0, 7, '1. Goods once sold will not be taken back.', 0, 1);
        $pdf->Cell(0, 7, '2. Intrest @18% p.a. will be charged if payment is not made within due date.', 0, 1);
        $pdf->Cell(0, 7, '3. Our risk and responsibility ceases as soon as the goods leave our premises.', 0, 1);
        $pdf->Cell(0, 7, "4. Subject to 'SURAT' Jurisdiction only E & O.E.", 0, 1);

        // $file = time() . '.pdf';
        // $pdf->Output($file, '');
        $pdf->Output();
    }
}
