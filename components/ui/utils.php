<?php
function cn(...$classes) {
    return implode(' ', array_filter($classes));
}