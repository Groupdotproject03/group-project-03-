<?php
/*
    crypto_helper.php
    ------------------
    Shared helper used by the Online Consultation chat and the 24/7 Chat
    Support module to encrypt message text before it is stored in the
    database, and decrypt it again only for the authorized participants
    who are allowed to view that specific conversation.

    Combined with serving the site over HTTPS (so data is also protected
    while travelling between browser and server), this keeps chat content
    unreadable to anyone who only has database access.

    NOTE: change CHAT_ENC_KEY to your own secret value before going live,
    and keep it out of version control in a real deployment.
*/

if (!defined('CHAT_ENC_KEY')) {
    define('CHAT_ENC_KEY', 'HealthChecker#2026$SecureChatKey!');
}
if (!defined('CHAT_ENC_METHOD')) {
    define('CHAT_ENC_METHOD', 'aes-256-cbc');
}

function encrypt_message($plaintext) {
    $key = substr(hash('sha256', CHAT_ENC_KEY, true), 0, 32);
    $ivlen = openssl_cipher_iv_length(CHAT_ENC_METHOD);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $cipher = openssl_encrypt($plaintext, CHAT_ENC_METHOD, $key, OPENSSL_RAW_DATA, $iv);
    if ($cipher === false) {
        return base64_encode($plaintext); // fallback, should not normally happen
    }
    return base64_encode($iv . $cipher);
}

function decrypt_message($encoded) {
    $key = substr(hash('sha256', CHAT_ENC_KEY, true), 0, 32);
    $data = base64_decode($encoded);
    if ($data === false) {
        return '';
    }
    $ivlen = openssl_cipher_iv_length(CHAT_ENC_METHOD);
    if (strlen($data) <= $ivlen) {
        return '';
    }
    $iv = substr($data, 0, $ivlen);
    $cipher = substr($data, $ivlen);
    $plain = openssl_decrypt($cipher, CHAT_ENC_METHOD, $key, OPENSSL_RAW_DATA, $iv);
    return $plain === false ? '[unable to decrypt message]' : $plain;
}
