@php
$hour = date('H');
$greeting = '';
$icon = '';
$message = '';

// Array of random messages for each time period
$morningMessages = [
'sudah sarapan belum?',
'semoga harimu menyenangkan!',
'jangan lupa sarapan ya!',
'siap memulai hari?'
];

$noonMessages = [
'sudah makan siang?',
'semoga aktivitasmu lancar!',
'jangan lupa istirahat ya!',
'tetap semangat!'
];

$afternoonMessages = [
'sudah minum kopi/teh sore?',
'semoga tetap produktif!',
'jangan lupa stretching ya!',
'masih semangat kan?'
];

$nightMessages = [
'sudah makan malam?',
'jangan tidur terlalu malam ya!',
'selamat beristirahat!',
'terima kasih untuk hari ini!'
];

if ($hour >= 3 && $hour < 11) { $greeting='Selamat Pagi' ; $icon='bi bi-brightness-high text-yellow-500' ;
    $message=$morningMessages[array_rand($morningMessages)]; } elseif ($hour>= 11 && $hour < 15) {
        $greeting='Selamat Siang' ; $icon='bi bi-sun text-yellow-500' ;
        $message=$noonMessages[array_rand($noonMessages)]; } elseif ($hour>= 15 && $hour < 18) {
            $greeting='Selamat Sore' ; $icon='bi bi-sunset text-orange-500' ;
            $message=$afternoonMessages[array_rand($afternoonMessages)]; } else { $greeting='Selamat Malam' ;
            $icon='bi bi-moon-stars text-blue-900' ; $message=$nightMessages[array_rand($nightMessages)]; }
            $name=auth()->check() ? explode(' ', auth()->user()->name)[0] : 'Dinsers';
            @endphp

            <div class="bg-white/80 backdrop-blur-sm shadow-lg rounded-lg p-4 mb-6">
                <div class="flex items-center gap-3">
                    <i class="{{ $icon }} text-2xl md:text-3xl"></i>
                    <div>
                        <h2 class="text-lg md:text-xl font-semibold text-gray-800">
                            {{ $greeting }}, {{ $name }}!
                        </h2>
                        <p class="text-sm text-gray-600">
                            @if(auth()->check())
                            {{ $message }}
                            @else
                            Selamat datang di <span class="text-custom-secondary">{{ substr($appSettings['app_name'], 0,
                                -4) }}</span><span class="text-custom">{{ substr($appSettings['app_name'], -4) }}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            @push('scripts')
            <script>
                // Arrays of messages for each time period
    const morningMessages = [
        'sudah sarapan belum?',
        'semoga harimu menyenangkan!',
        'jangan lupa sarapan ya!',
        'siap memulai hari?'
    ];

    const noonMessages = [
        'sudah makan siang?',
        'semoga aktivitasmu lancar!',
        'jangan lupa istirahat ya!',
        'tetap semangat!'
    ];

    const afternoonMessages = [
        'sudah minum kopi/teh sore?',
        'semoga tetap produktif!',
        'jangan lupa stretching ya!',
        'masih semangat kan?'
    ];

    const nightMessages = [
        'sudah makan malam?',
        'jangan tidur terlalu malam ya!',
        'selamat beristirahat!',
        'terima kasih untuk hari ini!'
    ];

    // Function to get random message from array
    const getRandomMessage = (messages) => {
        return messages[Math.floor(Math.random() * messages.length)];
    };

    // Update greeting every minute
    setInterval(() => {
        let hour = new Date().getHours();
        let greeting = '';
        let icon = '';
        let message = '';
        
        if (hour >= 3 && hour < 11) {
            greeting = 'Selamat Pagi';
            icon = 'bi bi-brightness-high text-yellow-500';
            message = getRandomMessage(morningMessages);
        } else if (hour >= 11 && hour < 15) {
            greeting = 'Selamat Siang';
            icon = 'bi bi-sun text-yellow-500';
            message = getRandomMessage(noonMessages);
        } else if (hour >= 15 && hour < 18) {
            greeting = 'Selamat Sore';
            icon = 'bi bi-sunset text-orange-500';
            message = getRandomMessage(afternoonMessages);
        } else {
            greeting = 'Selamat Malam';
            icon = 'bi bi-moon-stars text-blue-900';
            message = getRandomMessage(nightMessages);
        }

        document.querySelector('h2').textContent = `${greeting}, {{ $name }}!`;
        document.querySelector('i').className = icon;
        if (document.querySelector('p.text-sm.text-gray-600')) {
            @if(auth()->check())
            document.querySelector('p.text-sm.text-gray-600').textContent = message;
            @endif
        }
    }, 60000);
            </script>
            @endpush