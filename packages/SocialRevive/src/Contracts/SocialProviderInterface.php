<?php
interface SocialProviderInterface
{
    public function post($caption, $media = []);
}
