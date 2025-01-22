<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = Voucher::latest()->paginate(10);
        return view('admin.vouchers.index', compact('vouchers'));
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
    public function show(string $id)
    {
        //
    }

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

        $discount = $voucher->calculateDiscount($request->total_amount);

        return response()->json([
            'valid' => true,
            'discount' => $discount,
            'message' => 'Voucher berhasil diterapkan!'
        ]);
    }
}
