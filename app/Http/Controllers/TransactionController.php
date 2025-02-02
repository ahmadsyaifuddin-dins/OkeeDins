<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Orders;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Total pengeluaran untuk metode transfer (berhasil)
        $totalTransferSpent = Transaction::whereHas('order', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('payment_method', 'transfer')
                ->whereIn('status', ['processing', 'completed']);
        })->sum('amount');

        // Total pengeluaran untuk COD (berhasil)
        $totalCODSpent = Transaction::whereHas('order', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('payment_method', 'Cash on Delivery')
                ->where('status', 'completed');
        })->sum('amount');

        // Total pesanan transfer (semua status)
        $totalTransferOrders = Orders::where('user_id', $user->id)
            ->where('payment_method', 'transfer')
            ->count();

        // Total pesanan COD (semua status)
        $totalCODOrders = Orders::where('user_id', $user->id)
            ->where('payment_method', 'Cash on Delivery')
            ->count();

        // Total pesanan transfer yang berhasil
        $totalSuccessTransferOrders = Orders::where('user_id', $user->id)
            ->where('payment_method', 'transfer')
            ->whereIn('status', ['processing', 'completed'])
            ->count();

        // Total pesanan COD yang berhasil
        $totalSuccessCODOrders = Orders::where('user_id', $user->id)
            ->where('payment_method', 'Cash on Delivery')
            ->where('status', 'completed')
            ->count();

        // Total pesanan transfer yang menunggu pembayaran
        $totalPendingTransferOrders = Orders::where('user_id', $user->id)
            ->where('payment_method', 'transfer')
            ->where('status', 'pending')
            ->count();

        // Total pesanan transfer yang menunggu konfirmasi pembayaran
        $totalAwaitingConfirmPaymentTransferOrders = Orders::where('user_id', $user->id)
            ->where('payment_method', 'transfer')
            ->where('status', 'awaiting payment')
            ->count();

        // Total pesanan COD yang menunggu
        $totalPendingCODOrders = Orders::where('user_id', $user->id)
            ->where('payment_method', 'Cash on Delivery')
            ->whereIn('status', ['pending', 'processing'])
            ->count();

        // Get total spent this month
        $totalSpentThisMonth = Transaction::whereHas('order', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('status', 'completed')
            ->sum('amount');

        // Get total completed orders
        $totalCompletedOrders = Orders::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // Get transactions history with filter
        $query = Transaction::whereHas('order', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('order');

        // Filter berdasarkan status
        if ($request->status) {
            $status = $request->status;
            $query->where('status', $status);
        }

        // Urutkan berdasarkan tanggal terbaru
        $transactions = $query->latest()->paginate(5);

        return view('home.transaksi', compact(
            'totalTransferSpent',
            'totalCODSpent',
            'totalTransferOrders',
            'totalCODOrders',
            'totalSuccessTransferOrders',
            'totalSuccessCODOrders',
            'totalPendingTransferOrders',
            'totalAwaitingConfirmPaymentTransferOrders',
            'totalPendingCODOrders',
            'totalSpentThisMonth',
            'totalCompletedOrders',
            'transactions'
        ));
    }

    public function createTransaction($order)
    {
        try {
            // Cek apakah transaksi sudah ada
            $existingTransaction = Transaction::where('order_id', $order->id)->first();
            if ($existingTransaction) {
                return $existingTransaction;
            }

            // Set initial status
            $initialStatus = 'pending';
            $paymentStatus = 'unpaid';
            
            // Buat transaksi baru
            $transaction = new Transaction();
            $transaction->order_id = $order->id;
            // $transaction->user_id = auth()->id();
            $transaction->amount = $order->total_amount;
            $transaction->payment_method = $order->payment_method;
            $transaction->status = $initialStatus;
            $transaction->payment_status = $paymentStatus;
            $transaction->payment_date = null;
            $transaction->save();

            return $transaction;
        } catch (\Exception $e) {
            Log::error('Error in createTransaction: ' . $e->getMessage());
            Log::error('Order data: ' . json_encode($order->toArray()));
            throw $e;
        }
    }

    public function updateTransactionStatus($orderId, $status, $paymentMethod = null)
    {
        try {
            $transaction = Transaction::where('order_id', $orderId)->first();
            if (!$transaction) {
                Log::error('Transaction not found for order ID: ' . $orderId);
                return null;
            }

            // Update status
            $transaction->status = $status;
            
            // Update payment status dan date berdasarkan metode pembayaran dan status
            if (($paymentMethod === 'transfer' && $status === 'processing') || 
                ($paymentMethod === 'Cash on Delivery' && $status === 'completed')) {
                $transaction->payment_status = 'paid';
                if (!$transaction->payment_date) {
                    $transaction->payment_date = now();
                }
            }

            $transaction->save();
            return $transaction;

        } catch (\Exception $e) {
            Log::error('Error updating transaction status: ' . $e->getMessage());
            throw $e;
        }
    }
}