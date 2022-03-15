<?php

namespace App\Traits;

use App\Services\TwilioClient;

trait ProvidesTelephony
{
    public function sendAsSms()
    {
        $twilio = app()->make(TwilioClient::class);

        return $twilio->text(
            $this->destination,
            $this->hydrated_message,
        );
    }

    public function sendAsPhone()
    {
        $twilio = app()->make(TwilioClient::class);

        return $twilio->call(
            $this->destination,
            $this->hydrated_message,
        );
    }
}
