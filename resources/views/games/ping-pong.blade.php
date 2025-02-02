@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white text-center">Ping Pong Game</h2>
            </div>
            
            <div class="p-6">
                <div class="flex justify-center mb-6">
                    <canvas id="pingPongCanvas" width="600" height="400" class="border-2 border-gray-300 rounded-lg"></canvas>
                </div>
                
                <div class="text-center space-y-4">
                    <div class="bg-gray-50 rounded-lg p-4 inline-block">
                        <p class="text-lg font-medium text-gray-700">Skor: <span id="score" class="text-blue-600">0</span></p>
                    </div>
                    
                    <button id="startGame" 
                            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Mulai Game
                    </button>
                </div>
                
                <div class="mt-6 bg-blue-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-blue-900 mb-2">Cara Bermain:</h3>
                    <ul class="list-disc list-inside text-blue-800 space-y-1">
                        <li>Gunakan mouse untuk menggerakkan paddle</li>
                        <li>Jangan biarkan bola melewati paddle Anda</li>
                        <li>Dapatkan skor setinggi mungkin untuk mendapatkan voucher!</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const canvas = document.getElementById('pingPongCanvas');
    const ctx = canvas.getContext('2d');
    
    // Game objects
    const ball = {
        x: canvas.width/2,
        y: canvas.height/2,
        radius: 10,
        speedX: 5,
        speedY: 5
    };
    
    const paddle = {
        width: 10,
        height: 100,
        y: canvas.height/2 - 50,
        speed: 5
    };
    
    const player = {
        x: 50,
        score: 0
    };
    
    const computer = {
        x: canvas.width - 50,
        y: canvas.height/2 - 50
    };
    
    let gameRunning = false;
    
    // Event listeners
    document.getElementById('startGame').addEventListener('click', startGame);
    canvas.addEventListener('mousemove', (e) => {
        const rect = canvas.getBoundingClientRect();
        paddle.y = e.clientY - rect.top - paddle.height/2;
        
        // Keep paddle within canvas
        if (paddle.y < 0) paddle.y = 0;
        if (paddle.y + paddle.height > canvas.height) paddle.y = canvas.height - paddle.height;
    });
    
    function startGame() {
        if (!gameRunning) {
            gameRunning = true;
            gameLoop();
        }
    }
    
    function gameLoop() {
        if (!gameRunning) return;
        
        // Clear canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        // Move ball
        ball.x += ball.speedX;
        ball.y += ball.speedY;
        
        // Ball collision with top and bottom
        if (ball.y + ball.radius > canvas.height || ball.y - ball.radius < 0) {
            ball.speedY = -ball.speedY;
        }
        
        // Ball collision with paddles
        if (ball.x - ball.radius < player.x + paddle.width && 
            ball.y > paddle.y && 
            ball.y < paddle.y + paddle.height) {
            ball.speedX = -ball.speedX;
            player.score++;
            document.getElementById('score').textContent = player.score;
        }
        
        // Computer paddle movement
        const computerCenter = computer.y + paddle.height/2;
        if (computerCenter < ball.y - 35) {
            computer.y += paddle.speed;
        } else if (computerCenter > ball.y + 35) {
            computer.y -= paddle.speed;
        }
        
        // Ball collision with computer paddle
        if (ball.x + ball.radius > computer.x - paddle.width && 
            ball.y > computer.y && 
            ball.y < computer.y + paddle.height) {
            ball.speedX = -ball.speedX;
        }
        
        // Ball out of bounds
        if (ball.x < 0 || ball.x > canvas.width) {
            resetBall();
        }
        
        // Draw everything
        drawBall();
        drawPaddle(player.x, paddle.y);
        drawPaddle(computer.x, computer.y);
        
        requestAnimationFrame(gameLoop);
    }
    
    function drawBall() {
        ctx.beginPath();
        ctx.arc(ball.x, ball.y, ball.radius, 0, Math.PI*2);
        ctx.fillStyle = '#3B82F6'; // Tailwind blue-500
        ctx.fill();
        ctx.closePath();
    }
    
    function drawPaddle(x, y) {
        ctx.fillStyle = '#1E40AF'; // Tailwind blue-800
        ctx.fillRect(x - paddle.width/2, y, paddle.width, paddle.height);
    }
    
    function resetBall() {
        ball.x = canvas.width/2;
        ball.y = canvas.height/2;
        ball.speedX = -ball.speedX;
    }
</script>
@endpush
@endsection
