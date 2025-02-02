let zoomActive = false;

    function showModal(imgSrc) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        const spinner = document.getElementById('loadingSpinner');
        
        modalImg.src = imgSrc;
        spinner.style.display = 'flex';
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        zoomActive = false;
        modalImg.style.transform = 'scale(1)';
    }

    function hideModal() {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        
        zoomActive = false;
        modalImg.style.transform = 'scale(1)';
    }

    function toggleZoom(img) {
        if (!zoomActive) {
            img.style.transform = 'scale(2)';
            img.style.cursor = 'zoom-out';
            zoomActive = true;
        } else {
            img.style.transform = 'scale(1)';
            img.style.cursor = 'zoom-in';
            zoomActive = false;
        }
    }

    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideModal();
        }
    });