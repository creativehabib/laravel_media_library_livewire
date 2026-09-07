<?php

use Illuminate\Support\Facades\Http;

class FacebookService implements SocialProviderInterface
{
    protected $account;

    public function __construct($account)
    {
        $this->account = $account;
    }

    public function post($caption, $media = [])
    {
        $url = "https://graph.facebook.com/v19.0/me/feed";

        $response = Http::post($url, [
            'message' => $caption,
            'access_token' => $this->account->access_token,
        ]);

        if ($response->failed()) {
            throw new \Exception($response->body());
        }

        return $response->json();
    }
}
