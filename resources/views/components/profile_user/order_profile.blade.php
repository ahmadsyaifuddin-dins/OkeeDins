<div id="ordersContent" class="tab-content hidden">
    <div class="p-6">
        <div class="space-y-4">
            @forelse($orders as $order)
                <a href="{{ route('orders.detail', $order->id) }}" class="block">
                    <div
                        class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 transition duration-300 ease-in-out hover:shadow-md">
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <span class="text-sm text-gray-500">#{{ $order->order_number }}</span>
                                <span
                                    class="ml-2 text-sm font-medium text-custom">{{ Carbon\Carbon::parse($order->created_at)->locale('id')->isoFormat('DD MMMM YYYY') }}</span>
                            </div>
                            <span
                                class="px-3 py-1 text-sm rounded-full 
                                @if ($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'awaiting payment') bg-blue-100 text-blue-800
                                @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status == 'delivered') bg-blue-100 text-blue-800
                                @elseif($order->status == 'completed') bg-green-100 text-green-800
                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800 @endif">
                                {{ ucfirst($order->status_label) }}
                            </span>
                        </div>
                        <div class="border-t border-gray-100 pt-2">
                            <div class="text-sm text-gray-600">
                                Total Pembayaran: <span class="font-medium text-gray-900">Rp
                                    {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-custom">
                            <i class="bi bi-eye-fill mr-1"></i> Lihat Detail
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-8">
                    <i class="bi bi-bag-x text-4xl text-gray-400"></i>
                    <i class="bi bi-emoji-frown text-4xl text-gray-400 ml-2"></i>
                    <p class="mt-2 text-gray-500">Belum ada riwayat pesanan </p>
                </div>
            @endforelse
        </div>
    </div>
    <div class="mt-4 flex justify-center">
        {{ $orders->links('vendor.pagination.custom') }}
    </div>
</div>
