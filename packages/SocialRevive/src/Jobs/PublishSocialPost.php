<?php

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;

class PublishSocialPostJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public $tries = 5;

    public function __construct(public SocialPostQueue $post) {}

    public function handle()
    {
        $providerName = $this->post->account->provider;

        $provider = match($providerName) {
            'facebook' => new FacebookService($this->post->account),
            default => throw new \Exception('Unsupported provider')
        };

        $provider->post(
            $this->post->caption,
            $this->post->media
        );

        $this->post->update([
            'status' => 'posted',
            'posted_at' => now()
        ]);
    }

    public function failed($exception)
    {
        $this->post->increment('retry_count');

        $this->post->update([
            'status' => 'failed',
            'last_error' => $exception->getMessage()
        ]);
    }
}
