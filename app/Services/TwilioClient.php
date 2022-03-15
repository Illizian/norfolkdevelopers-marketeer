<?php

namespace App\Services;

use Twilio\Rest\Api\V2010\Account\CallInstance;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Client;
use Twilio\TwiML\VoiceResponse;

class TwilioClient
{
    protected Client $client;

    public function __construct(
        protected string $sid,
        protected string $token,
        protected string $from,
    ) {
        $this->client = new Client($this->sid, $this->token);
    }

    public static function make(
        string $sid,
        string $token,
        string $from,
    ): self {
        return new self($sid, $token, $from);
    }

    public function text(string $to, string $content = ''): MessageInstance
    {
        $client = new Client($this->sid, $this->token);

        return $client->messages->create(
            $to,
            [
                'from' => $this->from,
                'body' => $content
            ]
        );
    }

    public function call(string $to, string $content): CallInstance
    {
        $Twiml = (new VoiceResponse())
            ->say('Hello, this is a notification from NorDev Marketeer')
            ->pause([ 'length' => 2 ])
            ->say($content)
            ->asXML();

        return $this->client->calls->create(
            $to,
            $this->from,
            compact('Twiml')
        );
    }
}
