<?php
function toast($message, $type = 'default', $duration = 5000) {
    // Map type to color and icon
    $types = [
        'default' => ['bg-white', 'text-gray-900', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'],
        'success' => ['bg-green-100', 'text-green-900', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'],
        'error' => ['bg-red-100', 'text-red-900', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'],
        'warning' => ['bg-yellow-100', 'text-yellow-900', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>'],
        'info' => ['bg-blue-100', 'text-blue-900', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>']
    ];

    $bgColor = $types[$type][0] ?? $types['default'][0];
    $textColor = $types[$type][1] ?? $types['default'][1];
    $icon = $types[$type][2] ?? $types['default'][2];

    return <<<HTML
    <div
        x-data="{ show: false, message: '$message' }"
        x-show="show"
        x-init="() => {
            show = true;
            setTimeout(() => { show = false }, $duration);
        }"
        x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-4 right-4 w-full max-w-sm overflow-hidden rounded-lg shadow-lg pointer-events-auto"
        role="alert"
    >
        <div class="p-4 $bgColor border border-gray-200">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 $textColor" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        $icon
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1">
                    <p class="text-sm font-medium $textColor" x-text="message"></p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button
                        @click="show = false"
                        class="inline-flex $textColor hover:opacity-75 focus:outline-none"
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
    HTML;
}

// Helper functions for different toast types
function success_toast($message, $duration = 5000) {
    return toast($message, 'success', $duration);
}

function error_toast($message, $duration = 5000) {
    return toast($message, 'error', $duration);
}

function warning_toast($message, $duration = 5000) {
    return toast($message, 'warning', $duration);
}

function info_toast($message, $duration = 5000) {
    return toast($message, 'info', $duration);
}