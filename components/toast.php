<!-- Toast Wrapper: Tetap di tempat mana pun -->
<div id="toast-wrapper" style="position: fixed; top: 80px; right: 16px; z-index: 9999;"></div>

<!-- Toast Script -->
<script>
    function showToast(message, type = 'success') {
        const wrapper = document.getElementById('toast-wrapper');
        if (!wrapper) return;

        const icons = {
            success: `<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/></svg>`,
            error: `<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/></svg>`,
            warning: `<svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/></svg>`
        };

        const classes = {
            success: 'bg-white text-gray-800 border-l-4 border-green-500 shadow',
            error: 'bg-white text-gray-800 border-l-4 border-red-500 shadow',
            warning: 'bg-white text-gray-800 border-l-4 border-orange-400 shadow'
        };

        const toast = document.createElement('div');
        toast.className = `flex items-start max-w-xs w-full p-4 mb-2 rounded-lg ${classes[type] || ''} animate-slide-in-right`;
        toast.innerHTML = `
        <div class="mr-3">${icons[type]}</div>
        <div class="text-sm flex-1">${message}</div>
        <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600 ml-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;

        wrapper.appendChild(toast);

        setTimeout(() => toast.remove(), 4000);
    }
</script>

<style>
    @keyframes slide-in-right {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .animate-slide-in-right {
        animation: slide-in-right 0.3s ease-out;
    }
</style>