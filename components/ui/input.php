<?php
require_once __DIR__ . '/utils.php';

function Input($props = []) {
    $type = $props['type'] ?? 'text';
    $placeholder = $props['placeholder'] ?? '';
    $className = $props['className'] ?? '';
    $disabled = $props['disabled'] ?? false;
    $required = $props['required'] ?? false;
    $name = $props['name'] ?? '';
    $id = $props['id'] ?? $name;
    $value = $props['value'] ?? '';

    $baseClasses = 'flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50';

    $classes = cn($baseClasses, $className);

    return sprintf(
        '<input type="%s" id="%s" name="%s" placeholder="%s" value="%s" class="%s" %s %s />',
        htmlspecialchars($type),
        htmlspecialchars($id),
        htmlspecialchars($name),
        htmlspecialchars($placeholder),
        htmlspecialchars($value),
        htmlspecialchars($classes),
        $disabled ? 'disabled' : '',
        $required ? 'required' : ''
    );
}