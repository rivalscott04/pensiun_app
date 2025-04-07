<?php
require_once __DIR__ . '/button.php';

// Contoh penggunaan komponen Button
?>

<div class="space-y-4">
    <?php
    // Button Default
    echo Button([
        'children' => 'Default Button',
        'className' => 'w-full'
    ]);
    ?>

    <?php
    // Button Secondary
    echo Button([
        'variant' => 'secondary',
        'children' => 'Secondary Button',
        'className' => 'w-full'
    ]);
    ?>

    <?php
    // Button Destructive
    echo Button([
        'variant' => 'destructive',
        'children' => 'Destructive Button',
        'className' => 'w-full'
    ]);
    ?>

    <?php
    // Button Outline
    echo Button([
        'variant' => 'outline',
        'children' => 'Outline Button',
        'className' => 'w-full'
    ]);
    ?>

    <?php
    // Button Ghost
    echo Button([
        'variant' => 'ghost',
        'children' => 'Ghost Button',
        'className' => 'w-full'
    ]);
    ?>

    <?php
    // Button Link
    echo Button([
        'variant' => 'link',
        'children' => 'Link Button',
        'className' => 'w-full'
    ]);
    ?>
</div>