<?php

if (is_file(MFSP__CRYPTO_FILE)) {
} else {
    $crypto_file = fopen(MFSP__CRYPTO_FILE, "w") or die("Unable to open file!");

    $encrypt_method = MFSP__CRYPTO_CIPHER;
    $secret_key = generateUUID4();
    $secret_iv = generateUUID4();

    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    $key_b64 = base64_encode($key);
    $iv_b64 = base64_encode($iv);

    # Save key & iv
    $txt = "<?php\n";
    fwrite($crypto_file, $txt);

    $txt = "define('MFSP__CRYPTO_KEY', '$key_b64');\n";
    fwrite($crypto_file, $txt);

    $txt = "define('MFSP__CRYPTO_IV', '$iv_b64');\n";
    fwrite($crypto_file, $txt);

    # Close file
    fclose($crypto_file);
}

$txt = "";
$key = "";
$iv = "";
