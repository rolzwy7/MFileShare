<?php

function msfpEncrypt($content)
{
    $encrypt_method = "aes-256-cbc";
    $ret = openssl_encrypt(
        $content,
        $encrypt_method,
        base64_decode(MFSP__CRYPTO_KEY),
        0,
        base64_decode(MFSP__CRYPTO_IV)
    );
    return $ret;
}

function msfpDecrypt($content)
{
    $encrypt_method = "aes-256-cbc";
    $ret = openssl_decrypt(
        $content,
        $encrypt_method,
        base64_decode(MFSP__CRYPTO_KEY),
        0,
        base64_decode(MFSP__CRYPTO_IV)
    );
    return $ret;
}
