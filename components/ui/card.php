<?php
function Card($props = []) {
    $className = $props['className'] ?? '';
    $children = $props['children'] ?? '';

    return "<div class=\"rounded-lg border bg-card text-card-foreground shadow-sm $className\">$children</div>";
}

function CardHeader($props = []) {
    $className = $props['className'] ?? '';
    $children = $props['children'] ?? '';

    return "<div class=\"flex flex-col space-y-1.5 p-6 $className\">$children</div>";
}

function CardTitle($props = []) {
    $className = $props['className'] ?? '';
    $children = $props['children'] ?? '';

    return "<h3 class=\"text-2xl font-semibold leading-none tracking-tight $className\">$children</h3>";
}

function CardDescription($props = []) {
    $className = $props['className'] ?? '';
    $children = $props['children'] ?? '';

    return "<p class=\"text-sm text-muted-foreground $className\">$children</p>";
}

function CardContent($props = []) {
    $className = $props['className'] ?? '';
    $children = $props['children'] ?? '';

    return "<div class=\"p-6 pt-0 $className\">$children</div>";
}

function CardFooter($props = []) {
    $className = $props['className'] ?? '';
    $children = $props['children'] ?? '';

    return "<div class=\"flex items-center p-6 pt-0 $className\">$children</div>";
}