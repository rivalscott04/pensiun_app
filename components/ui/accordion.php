<?php
function accordion_item($title, $content, $id) {
    return <<<HTML
    <div class="border border-gray-200 rounded-lg mb-2 overflow-hidden transition-all duration-200 ease-in-out" x-data="{ open: true }">
        <button 
            @click="open = !open"
            class="w-full px-4 py-3 flex justify-between items-center bg-white hover:bg-gray-50 transition-colors duration-200"
            :aria-expanded="open"
            :aria-controls="'content-' + '$id'"
        >
            <span class="font-semibold text-left text-gray-800">$title</span>
            <svg 
                class="w-5 h-5 transform transition-transform duration-200" 
                :class="{'rotate-180': open}"
                xmlns="http://www.w3.org/2000/svg" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        <div 
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            :id="'content-' + '$id'"
            class="px-4 py-3 bg-white"
        >
            $content
        </div>
    </div>
    HTML;
}