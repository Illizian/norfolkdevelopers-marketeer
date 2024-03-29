# nor(DEV): Marketeer

A simple suite of automations to assist with marketing activities.

## Development

This is a Laravel application, built with the [Laravel Nova](https://nova.laravel.com/). You can use any standard Laravel development environment (e.g [Homestead](https://laravel.com/docs/homestead), [Valet](https://laravel.com/docs/8.x/valet) etc), or use the included Docker image and docker-compose specification.

### Installation

You'll need to grab a copy of Laravel Nova and place it at `/nova` then, whilst `composer` is installed within the `www` container, before you first launch you'll need to install dependencies and build resources with:

- `composer install`
- `npm install`
- `npm run prod`

Configure the application, by copying the template dotenv:

- `cp .env.example .env`

Then, complete the required fields, providing a `DISCORD_BOT_TOKEN`, `TWITTER_CONSUMER_KEY` etc, and configuring the `MAIL_MAILER`.

### Docker

This is a Laravel application. Local development is done using laravel/sail, just run:

```
composer install
./vendor/bin/sail up
```

### Discord

Connecting to the Bot. The Discord bot relies on a one-time websocket connection to establish it's presence on a Discord server (aka. Guild). Once connected, this application can communicate solely over the REST API.

Run the following command to either setup the Bot on a new server, or to connect to existing Bots.

- `docker-compose exec www php artisan discord:setup`
