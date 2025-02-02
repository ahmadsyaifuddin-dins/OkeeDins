@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white text-center">Gunting Batu Kertas</h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-2 gap-8 mb-8">
                    <div class="text-center">
                        <h3 class="text-xl font-medium text-gray-900 mb-4">Kamu</h3>
                        <div id="playerChoice" class="text-6xl mb-2">?</div>
                        <div class="bg-green-50 rounded-lg p-2">
                            <p class="text-green-800">Skor: <span id="playerScore" class="font-bold">0</span></p>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <h3 class="text-xl font-medium text-gray-900 mb-4">Komputer</h3>
                        <div id="computerChoice" class="text-6xl mb-2">?</div>
                        <div class="bg-red-50 rounded-lg p-2">
                            <p class="text-red-800">Skor: <span id="computerScore" class="font-bold">0</span></p>
                        </div>
                    </div>
                </div>
                
                <div id="result" class="text-center text-2xl font-bold text-gray-800 mb-8"></div>
                
                <div class="flex justify-center space-x-4 mb-8">
                    <button onclick="play('✌️')" class="transform hover:scale-110 transition-transform duration-200 text-4xl bg-white hover:bg-gray-50 rounded-full p-4 shadow-lg">✌️</button>
                    <button onclick="play('🗿')" class="transform hover:scale-110 transition-transform duration-200 text-4xl bg-white hover:bg-gray-50 rounded-full p-4 shadow-lg">🗿</button>
                    <button onclick="play('📄')" class="transform hover:scale-110 transition-transform duration-200 text-4xl bg-white hover:bg-gray-50 rounded-full p-4 shadow-lg">📄</button>
                </div>
                
                <div class="bg-green-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-green-900 mb-2">Cara Bermain:</h3>
                    <ul class="list-disc list-inside text-green-800 space-y-1">
                        <li>Pilih salah satu: Gunting (✌️), Batu (🗿), atau Kertas (📄)</li>
                        <li>Menangkan 10 ronde untuk mendapatkan voucher!</li>
                        <li>Gunting mengalahkan Kertas</li>
                        <li>Batu mengalahkan Gunting</li>
                        <li>Kertas mengalahkan Batu</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let playerScore = 0;
    let computerScore = 0;
    
    function play(playerChoice) {
        const choices = ['✌️', '🗿', '📄'];
        const computerChoice = choices[Math.floor(Math.random() * choices.length)];
        
        document.getElementById('playerChoice').textContent = playerChoice;
        document.getElementById('computerChoice').textContent = computerChoice;
        
        const result = getResult(playerChoice, computerChoice);
        const resultElement = document.getElementById('result');
        
        if (result === "Seri!") {
            resultElement.className = "text-center text-2xl font-bold text-gray-800 mb-8";
        } else if (result === "Kamu Menang! 🎉") {
            resultElement.className = "text-center text-2xl font-bold text-green-600 mb-8";
            if (playerScore === 10) {
                getRandomVoucher();
            }
        } else {
            resultElement.className = "text-center text-2xl font-bold text-red-600 mb-8";
        }
        
        resultElement.textContent = result;
        updateScores();
    }
    
    function getResult(player, computer) {
        if (player === computer) return "Seri!";
        
        if (
            (player === '✌️' && computer === '📄') ||
            (player === '🗿' && computer === '✌️') ||
            (player === '📄' && computer === '🗿')
        ) {
            playerScore++;
            return "Kamu Menang! 🎉";
        }
        
        computerScore++;
        return "Komputer Menang! 😢";
    }
    
    function updateScores() {
        document.getElementById('playerScore').textContent = playerScore;
        document.getElementById('computerScore').textContent = computerScore;
    }

    function getRandomVoucher() {
        fetch('/games/get-random-voucher')
        .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const voucher = data.voucher;
                    const voucherValue = voucher.type === 'percentage' ? 
                        `${voucher.value}%` : 
                        `Rp ${new Intl.NumberFormat('id-ID').format(voucher.value)}`;
                    
                    Swal.fire({
                        title: 'Selamat! 🎉',
                        html: `
                            <div class="bg-green-50 p-4 rounded-lg">
                                <p class="text-lg mb-2">Kamu mendapatkan voucher:</p>
                                <div class="bg-white p-3 rounded shadow-sm">
                                    <p class="text-green-600 font-bold text-2xl mb-1">${voucherValue}</p>
                                    <p class="text-sm text-gray-600">Kode: <span class="font-mono bg-gray-100 px-2 py-1 rounded">${voucher.code}</span></p>
                                    <p class="text-xs text-gray-500 mt-2">Berlaku sampai: ${voucher.valid_until}</p>
                                </div>
                            </div>
                        `,
                        confirmButtonText: 'Salin Kode',
                        showCancelButton: true,
                        cancelButtonText: 'Tutup',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            navigator.clipboard.writeText(voucher.code);
                            Swal.fire({
                                title: 'Kode Tersalin!',
                                text: 'Kode voucher telah disalin ke clipboard.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Oops!',
                        text: data.message,
                        icon: 'info'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Terjadi kesalahan saat mengambil voucher.',
                    icon: 'error'
                });
            });
    }
</script>
@endpush
@endsection
