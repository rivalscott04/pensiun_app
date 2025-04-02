<!-- Toast Notification -->
<div id="toast" class="fixed top-4 right-4 z-50 transform transition-all duration-300 ease-in-out translate-x-full">
    <div class="flex items-center p-4 mb-4 rounded-lg shadow-lg min-w-[300px]" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg me-3">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"></svg>
        </div>
        <div class="ms-3 text-sm font-normal" id="toastMessage"></div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 rounded-lg focus:ring-2 p-1.5 inline-flex items-center justify-center h-8 w-8" onclick="hideToast()">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
</div>

<script>
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    const icon = toast.querySelector('svg');

    // Reset classes
    toast.querySelector('.flex').className = 'flex items-center p-4 mb-4 rounded-lg shadow-lg min-w-[300px]';
    icon.innerHTML = '';

    if (type === 'success') {
        toast.querySelector('.flex').classList.add('text-green-800', 'bg-green-50');
        icon.innerHTML = '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>';
        toastMessage.textContent = 'Berhasil' + (message ? ': ' + message : '');
    } else if (type === 'error') {
        toast.querySelector('.flex').classList.add('text-red-800', 'bg-red-50');
        icon.innerHTML = '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>';
        toastMessage.textContent = 'Gagal' + (message ? ': ' + message : '');
    } else {
        toastMessage.textContent = message;
    }

    toastMessage.textContent = message;
    toast.classList.remove('translate-x-full');
    toast.classList.add('translate-x-0');

    setTimeout(() => {
        hideToast();
    }, 3000);
}

function hideToast() {
    const toast = document.getElementById('toast');
    toast.classList.remove('translate-x-0');
    toast.classList.add('translate-x-full');
}
</script>
</script>