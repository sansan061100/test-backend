<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Penjualan;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{

    public function generatePaymentNumber()
    {
        // format PAY-001 (PAY-002, PAY-003, ...)
        $lastPayment = Pembayaran::orderBy('id', 'desc')->first();

        if (!$lastPayment) {
            $number = 'PAY-001';
        } else {
            $number = 'PAY-' . sprintf('%03d', intval(substr($lastPayment->payment_number, 4)) + 1);
        }

        return $number;
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_number' => 'required',
            'payment' => 'required|numeric'
        ]);

        $penjualan = Penjualan::where('transaction_number', $request->transaction_number)->first();
        $pembayaran = Pembayaran::where('penjualan_id', $penjualan->id)->selectRaw('SUM(payment) as payment')->first();

        if (!$penjualan) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Transaction number not found'
            ], 404);
        } else {
            if ($pembayaran->payment + $request->payment > $penjualan->grand_total) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Payment is more than the grand total you can pay ' . ($penjualan->grand_total - $pembayaran->payment)
                ], 400);
            } else {
                $pembayaran = Pembayaran::create([
                    'payment_number' => $this->generatePaymentNumber(),
                    'penjualan_id' => $penjualan->id,
                    'payment' => $request->payment,
                    'date' => now()
                ]);

                return response()->json([
                    'status' => 'success',
                    'data' => $pembayaran
                ]);
            }
        }
    }
}
