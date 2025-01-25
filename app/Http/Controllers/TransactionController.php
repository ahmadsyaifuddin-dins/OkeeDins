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
    public function index()
    {
        $user = Auth::user();
        
        // Total pengeluaran untuk metode transfer (berhasil)
        $totalTransferSpent = Transaction::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('payment_method', 'transfer')
                  ->whereIn('status', ['processing', 'completed']);
        })->sum('amount');

        // Total pesanan transfer (semua status)
        $totalTransferOrders = Orders::where('user_id', $user->id)
            ->where('payment_method', 'transfer')
            ->count();

        // Total pesanan transfer yang berhasil
        $totalSuccessTransferOrders = Orders::where('user_id', $user->id)
            ->where('payment_method', 'transfer')
            ->whereIn('status', ['processing', 'completed'])
            ->count();

        // Total pesanan transfer yang menunggu konfirmasi
        $totalPendingTransferOrders = Orders::where('user_id', $user->id)
            ->where('payment_method', 'transfer')
            ->where('status', 'awaiting payment')
            ->count();

        // Get total spent this month
        $totalSpentThisMonth = Transaction::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->whereMonth('created_at', Carbon::now()->month)
        ->where('status', 'paid')
        ->sum('amount');

        // Get total completed orders
        $totalCompletedOrders = Orders::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // Get transactions history
        $transactions = Transaction::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with('order')
        ->orderBy('created_at', 'desc')
        ->get();

        return view('home.transaksi', compact(
            'totalTransferSpent',
            'totalTransferOrders',
            'totalSuccessTransferOrders',
            'totalPendingTransferOrders',
            'totalSpentThisMonth',
            'totalCompletedOrders',
            'transactions'
        ));
    }

    public function createTransaction($order)
    {
        try {
            return Transaction::create([
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'payment_method' => $order->payment_method,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_date' => DateTime::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('Y-m-d H:i:s'),
            ]);
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
            
            if ($transaction) {
                $transaction->status = $status;
                $transaction->payment_status = $status;
                if ($status === 'paid') {
                    $transaction->payment_date = now();
                }
                if ($paymentMethod) {
                    $transaction->payment_method = $paymentMethod;
                }
                $transaction->save();
            }

            return $transaction;
        } catch (\Exception $e) {
            Log::error('Error in updateTransactionStatus: ' . $e->getMessage());
            throw $e;
        }
    }
}
