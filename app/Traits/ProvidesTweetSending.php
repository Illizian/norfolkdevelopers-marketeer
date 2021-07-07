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

        return $twitter->post('statuses/update', $params);
    }
}
