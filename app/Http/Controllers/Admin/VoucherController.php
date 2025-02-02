<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB as FacadesDB;
use App\Http\Controllers\Controller;


class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = Voucher::latest()->paginate(5);
        $voucherActivities = FacadesDB::table('voucher_user')
            ->join('users', 'voucher_user.user_id', '=', 'users.id')
            ->join('vouchers', 'voucher_user.voucher_id', '=', 'vouchers.id')
            ->join('orders', 'voucher_user.order_id', '=', 'orders.id')
            ->select('users.name as user_name', 'vouchers.code as voucher_code', 'voucher_user.discount_amount', 'voucher_user.used_at')
            ->whereNotNull('voucher_user.used_at')
            ->orderBy('voucher_user.used_at', 'desc')
            ->take(5)
            ->get();
            
        return view('admin.vouchers.index', compact('vouchers', 'voucherActivities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.vouchers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:vouchers,code',
            'name' => 'required',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'required|numeric|min:0',
            'is_active' => 'required|in:0,1',
            'max_uses' => 'nullable|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
        ]);

        Voucher::create($validated);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Voucher $voucher)
    {
        $validated = $request->validate([
            'code' => 'required|unique:vouchers,code,' . $voucher->id,
            'name' => 'required',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'is_active' => 'required|in:0,1',
            'min_purchase' => 'required|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
        ]);

        $voucher->update($validated);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher berhasil dihapus!');
    }

    public function validateVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|exists:vouchers,code',
            'max_uses' => 'nullable|integer|exists:vouchers,max_uses',
            'total_amount' => 'required|numeric|min:0'
        ]);

        $voucher = Voucher::where('code', $request->code)->first();
        
        if (!$voucher->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Voucher tidak valid atau sudah kadaluarsa.'
            ]);
        }

        if ($request->total_amount < $voucher->min_purchase) {
            return response()->json([
                'valid' => false,
                'message' => 'Total pembelian minimum tidak terpenuhi.'
            ]);
        }

        if ($voucher->max_uses && $voucher->used_uses >= $voucher->max_uses) {
            return response()->json([
                'valid' => false,
                'message' => 'Voucher sudah digunakan sebanyak maksimum.'
            ]);
        }

        $discount = $voucher->calculateDiscount($request->total_amount);

        return response()->json([
            'valid' => true,
            'discount' => $discount,
            'message' => 'Voucher berhasil diterapkan!'
        ]);
    }
}
