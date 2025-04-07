<?php
require_once __DIR__ . '/utils.php';

function Button($props = []) {
    $variant = $props['variant'] ?? 'default';
    $size = $props['size'] ?? 'default';
    $className = $props['className'] ?? '';
    $disabled = $props['disabled'] ?? false;
    $type = $props['type'] ?? 'button';
    $children = $props['children'] ?? '';

    $variants = [
        'default' => 'bg-primary text-white hover:bg-primary/90',
        'destructive' => 'bg-destructive text-destructive-foreground hover:bg-destructive/90',
        'outline' => 'border border-input hover:bg-accent hover:text-accent-foreground',
        'secondary' => 'bg-secondary text-secondary-foreground hover:bg-secondary/80',
        'ghost' => 'hover:bg-accent hover:text-accent-foreground',
        'link' => 'underline-offset-4 hover:underline text-primary'
    ];

    $sizes = [
        'default' => 'h-10 py-2 px-4',
        'sm' => 'h-9 px-3 rounded-md',
        'lg' => 'h-11 px-8 rounded-md',
        'icon' => 'h-10 w-10'
    ];

    $baseClasses = 'inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50';

    $classes = cn(
        $baseClasses,
        $variants[$variant],
        $sizes[$size],
        $className
    );

    return sprintf(
        '<button type="%s" class="%s" %s>%s</button>',
        htmlspecialchars($type),
        htmlspecialchars($classes),
        $disabled ? 'disabled' : '',
        $children
    );
}