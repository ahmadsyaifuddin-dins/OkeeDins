<?php

namespace App\Exports;

use App\Models\Orders;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanPendapatanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Orders::where('status', 'completed')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [
                $this->startDate ? Carbon::parse($this->startDate) : Carbon::now()->startOfMonth(),
                $this->endDate ? Carbon::parse($this->endDate) : Carbon::now()->endOfMonth()
            ])
            ->with(['orderItems.produk', 'user'])
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Pesanan',
            'Tanggal',
            'Pelanggan',
            'Total Item',
            'Total Harga',
            'Status',
            'Metode Pembayaran'
        ];
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->created_at->format('d/m/Y'),
            $order->user->name,
            $order->orderItems->sum('quantity'),
            'Rp ' . number_format($order->total_amount, 0, ',', '.'),
            $order->status,
            $order->payment_method
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
