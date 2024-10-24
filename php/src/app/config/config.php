<?php
function loadEnv($path)
{
    // echo "Mencari file .env... di $path\n";

    if (!file_exists($path)) {
        throw new Exception("File .env tidak ditemukan!");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            $_SERVER[$name] = $value;
            $_ENV[$name] = $value;
        }
    }

    // echo "File .env berhasil di-load!\n " . $_ENV["DB_HOST"];
}

loadEnv(__DIR__ .'/../../../../.env');
