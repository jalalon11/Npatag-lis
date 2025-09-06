<!-- Birth Certificate Modal -->
<div class="modal fade" id="birthCertificateModal" tabindex="-1" aria-labelledby="birthCertificateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="birthCertificateModalLabel">
                    <i class="fas fa-file-alt"></i> Birth Certificate
                </h5>
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-outline-secondary btn-sm me-2" id="fullscreenBtn" title="Toggle Fullscreen">
                        <i class="fas fa-expand"></i>
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0" style="min-height: 500px; position: relative;">
                <!-- Content Container -->
                <div id="contentContainer" style="display: none; height: 80vh;">
                    <!-- Image Display -->
                    <div id="imageContainer" class="text-center p-3" style="display: none; height: 100%;">
                        <img id="birthCertificateImage" 
                             style="max-width: 100%; max-height: 100%; object-fit: contain;"
                             src="" alt="Birth Certificate">
                    </div>
                    
                    <!-- PDF Display -->
                    <iframe id="birthCertificateFrame" 
                            style="width: 100%; height: 100%; border: none; display: none;"
                            src="">
                    </iframe>
                </div>
                
                <!-- Error Message -->
                <div id="errorMessage" class="alert alert-danger m-3" style="display: none;">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Failed to load birth certificate. Please try again.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" id="downloadBtn" class="btn btn-primary" target="_blank" style="display: none;">
                    <i class="fas fa-download"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>
<style>
/* Fullscreen modal styles */
.modal.fullscreen {
    padding: 0 !important;
}

.modal.fullscreen .modal-dialog {
    width: 100vw;
    height: 100vh;
    margin: 0;
    max-width: none;
    max-height: none;
}

.modal.fullscreen .modal-content {
    height: 100vh;
    border: none;
    border-radius: 0;
}

.modal.fullscreen .modal-body {
    height: calc(100vh - 120px);
}

.modal.fullscreen #contentContainer {
    height: calc(100vh - 120px) !important;
}
</style>

<script>
function openBirthCertificateModal(url) {
    const modal = document.getElementById('birthCertificateModal');
    const contentContainer = document.getElementById('contentContainer');
    const imageContainer = document.getElementById('imageContainer');
    const image = document.getElementById('birthCertificateImage');
    const iframe = document.getElementById('birthCertificateFrame');
    const errorMessage = document.getElementById('errorMessage');
    const downloadBtn = document.getElementById('downloadBtn');
    const fullscreenBtn = document.getElementById('fullscreenBtn');

    function resetModal() {
        contentContainer.style.display = 'none';
        imageContainer.style.display = 'none';
        iframe.style.display = 'none';
        errorMessage.style.display = 'none';
        downloadBtn.style.display = 'none';
        image.src = '';
        iframe.src = '';
        modal.classList.remove('fullscreen');
        const icon = fullscreenBtn.querySelector('i');
        icon.className = 'fas fa-expand';
        fullscreenBtn.title = 'Toggle Fullscreen';
    }

    function showContent(element) {
        contentContainer.style.display = 'block';
        element.style.display = 'block';
        downloadBtn.style.display = 'inline-block';
    }

    function showError() {
        errorMessage.style.display = 'block';
    }

    function loadContent(url) {
        const imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.bmp', '.webp'];
        const isLikelyImage = imageExtensions.some(ext => url.toLowerCase().includes(ext));

        if (isLikelyImage) {
            image.onload = () => showContent(imageContainer);
            image.onerror = () => loadAsPDF(url);
            image.src = url;
        } else {
            loadAsPDF(url);
        }
    }

    function loadAsPDF(url) {
        const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
        iframe.src = url;

        const handleLoad = () => {
            if (iframeDoc.readyState === 'complete' || iframe.contentWindow.document.readyState === 'complete') {
                showContent(iframe);
            }
        };

        iframe.onload = handleLoad;
        iframe.onerror = showError;

        setTimeout(() => {
            if (contentContainer.style.display === 'none') {
                handleLoad();
            }
        }, 2000);
    }

    resetModal();
    downloadBtn.href = url;
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
    loadContent(url);

    fullscreenBtn.onclick = function() {
        const icon = fullscreenBtn.querySelector('i');
        if (modal.classList.contains('fullscreen')) {
            modal.classList.remove('fullscreen');
            icon.className = 'fas fa-expand';
            fullscreenBtn.title = 'Enter Fullscreen';
        } else {
            modal.classList.add('fullscreen');
            icon.className = 'fas fa-compress';
            fullscreenBtn.title = 'Exit Fullscreen';
        }
    };

    modal.addEventListener('hidden.bs.modal', resetModal);
}
</script>