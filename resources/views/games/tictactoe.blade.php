@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Tic Tac Toe</h1>
            <p class="mt-2 text-lg text-gray-600">Mainkan game klasik Tic Tac Toe melawan komputer!</p>
        </div>

        <!-- Game Board -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <!-- Status Game -->
            <div class="text-center mb-6">
                <p id="status" class="text-xl font-semibold text-gray-800">Giliran Anda (X)</p>
                <p id="score" class="mt-2 text-gray-600">Skor Anda: <span id="playerScore">0</span> - Komputer: <span id="computerScore">0</span></p>
            </div>

            <!-- Papan Permainan -->
            <div class="grid grid-cols-3 gap-2 max-w-sm mx-auto mb-6">
                @for ($i = 0; $i < 9; $i++)
                    <button class="cell w-full aspect-square bg-gray-100 rounded-lg text-4xl font-bold hover:bg-gray-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500" data-index="{{ $i }}"></button>
                @endfor
            </div>

            <!-- Tombol Reset -->
            <div class="text-center">
                <button id="resetBtn" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                    Reset Game
                </button>
            </div>
        </div>

        <!-- Panduan Bermain -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Cara Bermain</h2>
            <ul class="list-disc list-inside space-y-2 text-gray-600">
                <li>Anda bermain sebagai X dan komputer sebagai O</li>
                <li>Klik pada kotak kosong untuk menempatkan X</li>
                <li>Untuk menang, Anda harus membuat garis lurus (horizontal, vertikal, atau diagonal) dengan simbol Anda</li>
                <li>Jika semua kotak terisi dan tidak ada pemenang, permainan akan berakhir seri</li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cells = document.querySelectorAll('.cell');
    const status = document.getElementById('status');
    const resetBtn = document.getElementById('resetBtn');
    const playerScoreElement = document.getElementById('playerScore');
    const computerScoreElement = document.getElementById('computerScore');
    
    let board = Array(9).fill('');
    let gameActive = true;
    let playerScore = 0;
    let computerScore = 0;
    
    const winCombos = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8], // Horizontal
        [0, 3, 6], [1, 4, 7], [2, 5, 8], // Vertical
        [0, 4, 8], [2, 4, 6] // Diagonal
    ];

    function checkWinner(board) {
        for (let combo of winCombos) {
            if (board[combo[0]] && 
                board[combo[0]] === board[combo[1]] && 
                board[combo[0]] === board[combo[2]]) {
                return board[combo[0]];
            }
        }
        if (board.includes('')) return null;
        return 'tie';
    }

    function updateScore(winner) {
        if (winner === 'X') {
            playerScore++;
            playerScoreElement.textContent = playerScore;
        } else if (winner === 'O') {
            computerScore++;
            computerScoreElement.textContent = computerScore;
        }
    }

    function minimax(board, depth, isMaximizing) {
        const winner = checkWinner(board);
        if (winner === 'O') return 10 - depth;
        if (winner === 'X') return depth - 10;
        if (winner === 'tie') return 0;

        if (isMaximizing) {
            let bestScore = -Infinity;
            for (let i = 0; i < 9; i++) {
                if (board[i] === '') {
                    board[i] = 'O';
                    const score = minimax(board, depth + 1, false);
                    board[i] = '';
                    bestScore = Math.max(score, bestScore);
                }
            }
            return bestScore;
        } else {
            let bestScore = Infinity;
            for (let i = 0; i < 9; i++) {
                if (board[i] === '') {
                    board[i] = 'X';
                    const score = minimax(board, depth + 1, true);
                    board[i] = '';
                    bestScore = Math.min(score, bestScore);
                }
            }
            return bestScore;
        }
    }

    function computerMove() {
        let bestScore = -Infinity;
        let bestMove;

        for (let i = 0; i < 9; i++) {
            if (board[i] === '') {
                board[i] = 'O';
                const score = minimax(board, 0, false);
                board[i] = '';
                if (score > bestScore) {
                    bestScore = score;
                    bestMove = i;
                }
            }
        }

        board[bestMove] = 'O';
        cells[bestMove].textContent = 'O';
        cells[bestMove].classList.add('text-custom');

        const winner = checkWinner(board);
        if (winner) {
            endGame(winner);
        } else {
            status.textContent = 'Giliran Anda (X)';
            gameActive = true;
        }
    }

    function endGame(winner) {
        gameActive = false;
        if (winner === 'tie') {
            status.textContent = 'Permainan Seri! Memulai ulang...';
            setTimeout(() => {
                resetGame();
                status.textContent = 'Giliran Anda (X)';
            }, 1500);
        } else {
            status.textContent = winner === 'X' ? 'Anda Menang!' : 'Komputer Menang!';
            updateScore(winner);
            if (winner === 'X') {
                getRandomVoucher();
            }
        }
    }

    function getRandomVoucher() {
        fetch('/games/get-random-voucher')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Selamat!',
                        text: `Anda mendapatkan voucher: ${data.voucher.code}`,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                }
            });
    }

    function handleCellClick(e) {
        const cell = e.target;
        const index = cell.dataset.index;

        if (board[index] !== '' || !gameActive) return;

        board[index] = 'X';
        cell.textContent = 'X';
        cell.classList.add('text-custom-secondary');

        const winner = checkWinner(board);
        if (winner) {
            endGame(winner);
        } else {
            status.textContent = 'Komputer sedang berpikir...';
            gameActive = false;
            setTimeout(computerMove, 500);
        }
    }

    function resetGame() {
        board = Array(9).fill('');
        gameActive = true;
        status.textContent = 'Giliran Anda (X)';
        cells.forEach(cell => {
            cell.textContent = '';
            cell.classList.remove('text-custom-secondary', 'text-red-500');
        });
    }

    cells.forEach(cell => cell.addEventListener('click', handleCellClick));
    resetBtn.addEventListener('click', resetGame);
});
</script>
@endpush
@endsection