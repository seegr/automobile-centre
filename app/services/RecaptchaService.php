<?php

namespace App\Services;

class RecaptchaService
{
    public function __construct(
        private string $secretKey,
    )
    {}

    public function verifyRecaptcha(string $recaptchaToken): bool
    {
    $post_data = http_build_query(
        [
        'secret' => $this->secretKey,
        'response' => $recaptchaToken,
        'remoteip' => $_SERVER['REMOTE_ADDR'],
        ]
    );

    $opts = [
        'http' =>
        [
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $post_data,
        ]
    ];

    $context  = stream_context_create($opts);
    $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
    $result = json_decode($response);

    if ($result->success && $result->score >= 0.5) {
        return true;
    }else{
        return false;
    }
    }
}