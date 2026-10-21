<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->orderBy('id', 'desc')->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);

        $order->load('items.medicine');

        return view('customer.orders.show', compact('order'));
    }

    public function invoice(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);

        $order->load('items.medicine', 'user');

        $pdfContent = $this->generateInvoicePdf($order);

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="invoice-order-'.$order->id.'.pdf"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    protected function generateInvoicePdf(Order $order): string
    {
        $content = "q\n";
        $content .= "0.10 0.52 0.33 rg\n";
        $content .= "50 760 495 80 re f\n";
        $content .= "Q\n";

        $textBlocks = [
            ['text' => 'MediCart', 'size' => 22, 'x' => 60, 'y' => 812, 'color' => '1 1 1 rg'],
            ['text' => 'Invoice', 'size' => 18, 'x' => 420, 'y' => 812, 'color' => '1 1 1 rg'],
            ['text' => '#'.$order->id, 'size' => 13, 'x' => 420, 'y' => 794, 'color' => '1 1 1 rg'],
            ['text' => 'Date: '.now()->format('d M Y, h:i A'), 'size' => 11, 'x' => 420, 'y' => 778, 'color' => '1 1 1 rg'],
            ['text' => 'Bill To', 'size' => 12, 'x' => 60, 'y' => 700, 'color' => '0 0 0 rg'],
            ['text' => $order->user->name, 'size' => 11, 'x' => 60, 'y' => 684, 'color' => '0 0 0 rg'],
            ['text' => $order->user->email, 'size' => 11, 'x' => 60, 'y' => 668, 'color' => '0 0 0 rg'],
            ['text' => $order->recipient_phone, 'size' => 11, 'x' => 60, 'y' => 652, 'color' => '0 0 0 rg'],
            ['text' => 'Shipping Address', 'size' => 12, 'x' => 320, 'y' => 700, 'color' => '0 0 0 rg'],
            ['text' => $order->shipping_address, 'size' => 11, 'x' => 320, 'y' => 684, 'color' => '0 0 0 rg'],
            ['text' => $order->shipping_city, 'size' => 11, 'x' => 320, 'y' => 668, 'color' => '0 0 0 rg'],
            ['text' => 'Order Summary', 'size' => 12, 'x' => 60, 'y' => 620, 'color' => '0 0 0 rg'],
        ];

        foreach ($textBlocks as $block) {
            $content .= "BT\n/F1 {$block['size']} Tf\n{$block['color']}\n1 0 0 1 {$block['x']} {$block['y']} Tm\n({$this->escapePdfText($block['text'])}) Tj\nET\n";
        }

        $content .= "q\n0.94 0.96 0.97 rg\n50 585 495 26 re f\nQ\n";
        $tableHeader = [
            ['text' => 'Medicine', 'x' => 60, 'y' => 598],
            ['text' => 'Price', 'x' => 300, 'y' => 598],
            ['text' => 'Qty', 'x' => 390, 'y' => 598],
            ['text' => 'Subtotal', 'x' => 450, 'y' => 598],
        ];

        foreach ($tableHeader as $header) {
            $content .= "BT\n/F1 11 Tf\n0 0 0 rg\n1 0 0 1 {$header['x']} {$header['y']} Tm\n({$this->escapePdfText($header['text'])}) Tj\nET\n";
        }

        $y = 570;
        foreach ($order->items as $item) {
            $itemY = $y + 15;

            $content .= "q\n0.98 0.99 1 rg\n50 {$y} 495 22 re f\nQ\n";
            $content .= "BT\n/F1 10 Tf\n0 0 0 rg\n1 0 0 1 60 {$itemY} Tm\n({$this->escapePdfText($item->medicine_name)}) Tj\nET\n";
            $content .= "BT\n/F1 10 Tf\n1 0 0 1 300 {$itemY} Tm\n({$this->escapePdfText('BDT '.number_format($item->price, 2))}) Tj\nET\n";
            $content .= "BT\n/F1 10 Tf\n1 0 0 1 390 {$itemY} Tm\n({$this->escapePdfText((string) $item->quantity)}) Tj\nET\n";
            $content .= "BT\n/F1 10 Tf\n1 0 0 1 450 {$itemY} Tm\n({$this->escapePdfText('BDT '.number_format($item->subtotal, 2))}) Tj\nET\n";
            $y -= 24;
        }

        $summaryY = 430;
        $summaryTitleY = $summaryY + 96;
        $summarySubtotalY = $summaryY + 72;
        $summaryDeliveryY = $summaryY + 54;
        $summaryTotalY = $summaryY + 24;

        $content .= "q\n0.96 0.98 0.96 rg\n330 {$summaryY} 215 120 re f\nQ\n";
        $content .= "BT\n/F1 11 Tf\n0 0 0 rg\n1 0 0 1 345 {$summaryTitleY} Tm\n(Total Summary) Tj\nET\n";
        $content .= "BT\n/F1 10 Tf\n1 0 0 1 345 {$summarySubtotalY} Tm\n(Subtotal: BDT ".number_format($order->subtotal, 2).") Tj\nET\n";
        $content .= "BT\n/F1 10 Tf\n1 0 0 1 345 {$summaryDeliveryY} Tm\n(Delivery: BDT ".number_format($order->delivery_fee, 2).") Tj\nET\n";
        $content .= "BT\n/F1 12 Tf\n1 0 0 1 345 {$summaryTotalY} Tm\n(Total: BDT ".number_format($order->total_amount, 2).") Tj\nET\n";

        $content .= "BT\n/F1 9 Tf\n0.40 0.40 0.40 rg\n1 0 0 1 50 70 Tm\n(Thank you for shopping with MediCart) Tj\nET\n";

        $objects = [];
        $objects[] = "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
        $objects[] = "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n";
        $objects[] = "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>\nendobj\n";
        $objects[] = "4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n";
        $objects[] = "5 0 obj\n<< /Length " . strlen($content) . " >>\nstream\n{$content}\nendstream\nendobj\n";

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $object) {
            $offsets[] = strlen($pdf);
            $pdf .= $object;
        }

        $xrefPosition = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        $pdf .= "trailer\n";
        $pdf .= "<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n";
        $pdf .= $xrefPosition . "\n";
        $pdf .= "%%EOF";

        return $pdf;
    }

    protected function escapePdfText(string $value): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $value);
    }
}
