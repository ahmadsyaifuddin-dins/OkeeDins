<div class="py-8">
    <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-6">Rekomendasi Untukmu</h2>

    <!-- Skeleton Loading -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 animate-pulse" id="skeleton-loader">
        @for ($i = 0; $i < 10; $i++) <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex flex-col gap-4">
                <div class="skeleton h-32 w-full rounded-lg bg-gray-300"></div>
                <div class="space-y-3">
                    <div class="skeleton h-4 w-3/4 bg-gray-300"></div>
                    <div class="skeleton h-4 w-1/2 bg-gray-300"></div>
                    <div class="skeleton h-4 w-full bg-gray-300"></div>
                </div>
            </div>
    </div>
    @endfor
</div>

<!-- Actual Products -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 hidden" id="actual-products">
    @foreach ($recommendedProducts as $product)
    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 h-fit">
        <a href="{{ route('produk.detail', $product->slug) }}" class="block">
            <!-- Image Container -->
            <div class="relative aspect-square">
                <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama_produk }}"
                    class="w-full h-full object-contain bg-gray-100 p-2 rounded-lg" loading="lazy">
                @if ($product->diskon > 0)
                <div class="absolute top-2 left-2">
                    <span class="bg-custom text-white px-2 py-1 rounded-md text-xs">
                        {{ number_format($product->diskon, 0) }}%
                    </span>
                </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="p-4 flex flex-col">
                <!-- Product Name -->
                <h3
                    class="text-sm md:text-base font-medium text-gray-900 hover:text-custom transition-colors duration-200 mb-2 truncate">
                    {{ $product->nama_produk }}
                </h3>

                <!-- Price Section -->
                <div class="mb-2">
                    <!-- Price Container with responsive layout -->
                    <div class="flex flex-col xl:flex-row xl:items-center xl:space-x-2">
                        <!-- Discounted Price -->
                        <span class="text-lg md:text-xl font-bold text-custom">
                            Rp{{ number_format($product->harga_diskon, 0, ',', '.') }}
                        </span>

                        <!-- Original Price (if discounted) -->
                        @if ($product->diskon > 0)
                        <span class="text-base text-gray-600 line-through mt-0.5 md:mt-0">
                            Rp{{ number_format($product->harga, 0, ',', '.') }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Rating & Sales Info -->
                <div class="flex items-center space-x-2 text-sm text-gray-500">
                    <div class="flex items-center">
                        <i class="bi bi-star-fill text-yellow-400 mr-1"></i>
                        <span>{{ number_format($product->getRatingAttribute(), 1) }}</span>
                    </div>
                    <span class="text-gray-300">•</span>
                    <span>{{ $product->getTotalTerjualAttribute() }} terjual</span>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const skeletonLoader = document.getElementById('skeleton-loader');
        const actualProducts = document.getElementById('actual-products');
        const CACHE_KEY = 'recommended_products_cache';
        const CACHE_DURATION = 30 * 60 * 1000; // 30 minutes in milliseconds

        // Function to check if cache exists and is valid
        const getValidCache = () => {
            const cached = localStorage.getItem(CACHE_KEY);
            if (!cached) return null;

            const { timestamp, data } = JSON.parse(cached);
            const isValid = (Date.now() - timestamp) < CACHE_DURATION;

            console.log('Cache status:', isValid ? 'Valid' : 'Expired', 
                    'Age:', Math.round((Date.now() - timestamp) / 1000), 'seconds');

            return isValid ? data : null;
        };

        // Function to update cache
        const updateCache = (data) => {
            localStorage.setItem(CACHE_KEY, JSON.stringify({
                timestamp: Date.now(),
                data
            }));
        };

        // Function to measure connection speed
        const measureConnectionSpeed = async () => {
            const startTime = performance.now();
            try {
                // Try to load a small image to test connection
                const response = await fetch('{{ asset('storage/' . ($appSettings['favicon'] ?? 'default-favicon.ico')) }}');
                const endTime = performance.now();
                return endTime - startTime;
            } catch (error) {
                console.error('Error measuring connection:', error);
                return 1000; // Default to 1000ms if measurement fails
            }
        };

        // Function to get device performance score (0-1)
        const getDevicePerformance = () => {
            const memory = navigator?.deviceMemory || 4; // Default to 4GB if not available
            const cores = navigator?.hardwareConcurrency || 4; // Default to 4 cores
            const connectionType = navigator?.connection?.effectiveType || '4g';

            // Calculate performance score
            const memoryScore = Math.min(memory / 8, 1); // Normalize to 0-1
            const coreScore = Math.min(cores / 8, 1); // Normalize to 0-1
            const connectionScore = {
                'slow-2g': 0.1,
                '2g': 0.3,
                '3g': 0.7,
                '4g': 1
            }[connectionType] || 0.7;

            return (memoryScore + coreScore + connectionScore) / 3;
        };

        // Modified loading function
        const handleLoading = async () => {
            console.group('Product Loading Performance');
            console.time('Total Loading Time');

            // Check for valid cache first
            const cachedData = getValidCache();
            if (cachedData) {
                console.log('Using cached data');
                skeletonLoader.style.display = 'none';
                actualProducts.classList.remove('hidden');
                console.timeEnd('Total Loading Time');
                console.groupEnd();
                return;
            }

            // If no cache, proceed with normal loading
            const connectionSpeed = await measureConnectionSpeed();
            console.log('Connection Speed:', Math.round(connectionSpeed) + 'ms');
            
            const deviceScore = getDevicePerformance();
            console.log('Device Performance Score:', Math.round(deviceScore * 100) + '%');

            // Calculate optimal loading time
            let loadingTime = Math.max(
                800,
                Math.min(connectionSpeed * (1 / deviceScore), 3000)
            );
            console.log('Calculated Loading Time:', loadingTime + 'ms');

            // Check images loading status
            const images = actualProducts.getElementsByTagName('img');
            const imagesLoaded = Array.from(images).every(img => img.complete);
            console.log('Images Pre-loaded:', imagesLoaded ? 'Yes' : 'No');

            if (!imagesLoaded) {
                loadingTime += 500;
                console.log('Added extra time for images:', loadingTime + 'ms');
            }

            // Apply the loading time
            setTimeout(() => {
                console.timeEnd('Total Loading Time');
                console.groupEnd();
                
                skeletonLoader.style.opacity = '0';
                setTimeout(() => {
                    skeletonLoader.style.display = 'none';
                    actualProducts.classList.remove('hidden');
                    actualProducts.style.opacity = '0';
                    requestAnimationFrame(() => {
                        actualProducts.style.opacity = '1';
                    });
                }, 300); // Fade out transition
            }, loadingTime);

            // After loading completes, cache the results
            const productData = Array.from(actualProducts.children).map(el => ({
                html: el.outerHTML,
                images: Array.from(el.getElementsByTagName('img')).map(img => img.src)
            }));
            updateCache(productData);

            console.log('Data cached for future visits');
            console.timeEnd('Total Loading Time');
            console.groupEnd();
        };

        // Clear cache when network status changes significantly
        if ('connection' in navigator) {
            navigator.connection.addEventListener('change', (e) => {
                if (e.type === '4g' || e.type === 'wifi') return;
                localStorage.removeItem(CACHE_KEY);
                console.log('Cache cleared due to network change');
            });
        }

        handleLoading();
    });
</script>

<style>
    #skeleton-loader,
    #actual-products {
        transition: opacity 0.3s ease-in-out;
    }
</style>