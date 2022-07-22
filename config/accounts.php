<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Available Account Providers
    |--------------------------------------------------------------------------
    |
    | The Account providers that are available and implement the
    | AccountInterface contract. An Account Model can specify
    | this type to send a Post via the provider specified
    |
    */

    'types' => [
        \App\Accounts\Twitter\TwitterAccount::class,
    ]

];
