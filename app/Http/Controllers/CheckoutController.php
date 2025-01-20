<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans;

class CheckoutController extends Controller

{
    public function checkout(Request $request){
// Validasi data dari frontend

$validated = $request->validate([
    'items' => 'required|array',
    'items.*.idProduct' => 'required|string',
    'items.*.price' => 'required|numeric',
    'items.*.quantity' => 'required|integer|min:1',
    'items.*.name' => 'required|string',
]);

// Set konfigurasi Midtrans
\Midtrans\Config::$serverKey = config('midtrans.server_key');
\Midtrans\Config::$isProduction = config('midtrans.is_production');
\Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
\Midtrans\Config::$is3ds = config('midtrans.is_3ds');

// Membuat order ID unik
$orderId = 'ORD-' . uniqid();

// Data transaksi
$transactionDetails = [
    'order_id' => $orderId,
    'gross_amount' => $validatedData['total'], // Total harga
];

// Format item details untuk Midtrans
$itemDetails = array_map(function ($item) {
    return [
        'id' => $item['product']['id'],
        'price' => $item['product']['price'],
        'quantity' => $item['quantity'],
        'name' => $item['product']['name'],
    ];
}, $validatedData['items']);

// Data pelanggan
$customerDetails = [
    'first_name' => $validatedData['customer']['first_name'],
    'last_name' => $validatedData['customer']['last_name'] ?? '',
    'email' => $validatedData['customer']['email'],
    'phone' => $validatedData['customer']['phone'],
];

// Data transaksi lengkap
$transaction = [
    'transaction_details' => $transactionDetails,
    'item_details' => $itemDetails,
    'customer_details' => $customerDetails,
];

try {
    // Buat transaksi dengan Midtrans
    $snapToken = \Midtrans\Snap::getSnapToken($transaction);

    // Kembalikan token ke frontend
    return response()->json([
        'snap_token' => $snapToken,
        'order_id' => $orderId,
    ]);
} catch (\Exception $e) {
    return response()->json([
        'error' => $e->getMessage(),
    ], 500);
}
    }

   

}
