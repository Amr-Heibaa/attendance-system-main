<?php

require __DIR__ . '/vendor/autoload.php';

$regularTtf = __DIR__ . '/public/fonts/Amiri-Regular.ttf';
$boldTtf    = __DIR__ . '/public/fonts/Amiri-Bold.ttf';

// Check files exist
if (!file_exists($regularTtf)) {
    die("Error: Amiri-Regular.ttf not found in public/fonts/\n");
}
if (!file_exists($boldTtf)) {
    die("Error: Amiri-Bold.ttf not found in public/fonts/\n");
}

$options = new Dompdf\Options();
$options->set('fontDir',   __DIR__ . '/vendor/dompdf/dompdf/lib/fonts/');
$options->set('fontCache', __DIR__ . '/vendor/dompdf/dompdf/lib/fonts/');

$dompdf = new Dompdf\Dompdf($options);
$fontMetrics = new Dompdf\FontMetrics($dompdf->getCanvas(), $options);

$fontMetrics->registerFont(
    ['family' => 'Amiri', 'style' => 'normal', 'weight' => 'normal'],
    $regularTtf
);

$fontMetrics->registerFont(
    ['family' => 'Amiri', 'style' => 'normal', 'weight' => 'bold'],
    $boldTtf
);

echo "✓ Font Amiri registered successfully!\n";