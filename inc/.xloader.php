<?php
try {
    $base = realpath(__DIR__ . '/../');
    $p1 = $base . '/app/core/.d1x.php';
    $p2 = $base . '/engine/system/.d2k.php';
    $p3 = $base . '/includes/modules/.lic.php';

    foreach ([$p1, $p2, $p3] as $f) {
        if (!file_exists($f)) {
            exit("Error: Falta el archivo crítico. El sistema se ha detenido.");
        }
    }

    include($p1);
    include($p2);
    include($p3);

    if (!isset($__f1, $__f2, $__f3)) {
        exit("Error: Los fragmentos críticos no están definidos. El sistema se ha detenido.");
    }

    $raw = $__f1 . $__f2 . $__f3;
    $key_part1 = '5da283a2d990';
    $key_part2 = 'e8d8';
    $key = $key_part1 . $key_part2;

    $footerContent = openssl_decrypt(base64_decode($raw), 'AES-128-ECB', $key, OPENSSL_RAW_DATA);

    if (!$footerContent || strpos($footerContent, 'ConfiguroWeb') === false) {
        exit("Error: La desencriptación falló o el contenido fue modificado. El sistema se ha detenido.");
    }

    echo '<div class="protected-footer" style="position:fixed;bottom:0;right:0;opacity:0.6;z-index:99999999;font-size:16px;padding:10px;">';
    echo $footerContent;
    echo '</div>';
} catch (Throwable $e) {
    exit("Error crítico: " . $e->getMessage());
}
?>