<?php
function loadEnv($path) {
    if (!file_exists($path)) {
        // Pas de fichier env
        echo "Fichier .env non trouvé à $path<br>";
        return;
    }
        

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue; // Ignore les commentaires
        [$name, $value] = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
    }
}
?>