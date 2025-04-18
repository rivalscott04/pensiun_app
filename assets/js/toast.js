function showToast(message, type = 'default', duration = 5000) {
    // Create a new div element for the toast
    const toastContainer = document.createElement('div');
    
    // Map type to color and icon
    const types = {
        'default': ['bg-white', 'text-gray-900', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'],
        'success': ['bg-green-100', 'text-green-900', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'],
        'error': ['bg-red-100', 'text-red-900', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'],
        'warning': ['bg-yellow-100', 'text-yellow-900', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>'],
        'info': ['bg-blue-100', 'text-blue-900', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>']
    };

    const [bgColor, textColor, icon] = types[type] || types['default'];

    // Set the toast HTML content
    toastContainer.innerHTML = `
        <div
            class="fixed bottom-4 right-4 w-full max-w-sm overflow-hidden rounded-lg shadow-lg pointer-events-auto transition-all transform translate-y-0 opacity-100"
            role="alert"
        >
            <div class="p-4 ${bgColor} border border-gray-200">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 ${textColor}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            ${icon}
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <p class="text-sm font-medium ${textColor}">${message}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button
                            onclick="this.closest('[role=\'alert\']').remove()"
                            class="inline-flex ${textColor} hover:opacity-75 focus:outline-none"
                        >
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Add the toast to the document
    document.body.appendChild(toastContainer);

    // Remove the toast after duration
    setTimeout(() => {
        if (toastContainer && document.body.contains(toastContainer)) {
            toastContainer.remove();
        }
    }, duration);
}