<?php

namespace App\Traits;

use Abraham\TwitterOAuth\TwitterOAuth;

trait ProvidesTweetSending {
    public function sendAsTweet()
    {
        $twitter = new TwitterOAuth(
            config('services.twitter.consumer_key'),
            config('services.twitter.consumer_secret'),
            config('services.twitter.access_token'),
            config('services.twitter.access_secret')
        );

        $params = [
            'status' => $this->hydrated_message,
            'response_format' => 'json'
        ];

        if (
            $this->reply &&
            $this->reply->sent &&
            $this->reply->response
        ) {
            // If the assigned ScheduledNotification has been sent, and has a response - use it
            $params['in_reply_to_status_id'] = json_decode($this->reply->response)->id_str;
        }

        // Extract any featured imagery
        $images = $this->getMedia('featured');

        if ($images->count()) {
            // If this ScheduledNotification has featured images, upload and assign it's ID to params
            $params['media_ids'] = $images
                ->map(fn($image) => $twitter->upload('media/upload', [ 'media' => $image->getPath() ]))
                ->implode('media_id_string', ',');
        }

        return $twitter->post('statuses/update', $params);
    }
}
