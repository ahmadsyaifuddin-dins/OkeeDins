<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    public function index()
    {
        return view('games.index');
    }

    public function pingPong()
    {
        return view('games.ping-pong');
    }

    public function rockPaperScissors()
    {
        return view('games.rock-paper-scissors');
    }

    public function getRandomVoucher()
    {
        try {
            $currentTime = now();
            
            // Ambil voucher yang masih valid dan aktif
            $validVoucher = Voucher::where('valid_until', '>', $currentTime)
                ->where('is_active', true)
                ->where(function($query) {
                    $query->whereNull('max_uses')
                        ->orWhere('used_count', '<', DB::raw('max_uses'));
                })
                ->inRandomOrder()
                ->first();

            if (!$validVoucher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maaf, tidak ada voucher yang tersedia saat ini.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'voucher' => [
                    'code' => $validVoucher->code,
                    'type' => $validVoucher->type,
                    'value' => $validVoucher->value,
                    'valid_until' => $validVoucher->valid_until->format('d M Y')
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil voucher.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
