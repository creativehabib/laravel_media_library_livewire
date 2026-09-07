<?php

use Illuminate\Console\Command;

class PublishScheduledPosts extends Command
{
    protected $signature = 'social:publish';

    public function handle()
    {
        $posts = SocialPostQueue::where('status', 'pending')
            ->where('scheduled_at', '<=', now())
            ->get();

        foreach ($posts as $post) {
            dispatch(new PublishSocialPostJob($post));
        }
    }
}
