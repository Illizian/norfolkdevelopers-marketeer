<?php

namespace Tests\Unit;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Models\Event;
use App\Models\ScheduledNotification;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use NotificationChannels\Twitter\TwitterChannel;
use Spatie\MediaLibrary\InteractsWithMedia;
use Tests\TestCase;

class ProvidesTweetSendingTest extends TestCase
{
    public function test_should_send_a_basic_tweet()
    {
        $this->mock(TwitterOAuth::class, fn($mock) =>
            $mock->shouldReceive('post')
                ->with(
                    'statuses/update',
                    [
                        "status" => "This is a test tweet",
                        "response_format" => "json",
                    ]
                )
                ->once()
        );

        $notification = ScheduledNotification::factory()->create([
            'type' => TwitterChannel::class,
            'message' => 'This is a test tweet',
            'sent' => false,
            'scheduled_at' => now(),
        ]);

        $notification->sendAsTweet();
    }

    public function test_should_send_a_tweet_in_reply()
    {
        $this->mock(TwitterOAuth::class, fn($mock) =>
            $mock->shouldReceive('post')
                ->with(
                    'statuses/update',
                    [
                        "status" => "This is a test tweet",
                        "response_format" => "json",
                        "in_reply_to_status_id" => "status_id",
                    ]
                )
                ->once()
        );

        $notification = ScheduledNotification::factory()
            ->forReply([
                'type' => TwitterChannel::class,
                'sent' => true,
                'response' => '{ "id_str": "status_id" }',
            ])
            ->create([
                'type' => TwitterChannel::class,
                'destination' => '',
                'message' => 'This is a test tweet',
                'sent' => false,
                'scheduled_at' => now(),
            ]);


        $notification->sendAsTweet();
    }

    public function test_should_send_a_tweet_with_a_featured_image()
    {
        $path = Storage::fake('public')
            ->getDriver()
            ->getAdapter()
            ->getPathPrefix();

        $notification = ScheduledNotification::factory()->create([
            'type' => TwitterChannel::class,
            'message' => 'This is a test tweet',
            'sent' => false,
            'scheduled_at' => now(),
        ]);

        $media = $notification
            ->addMedia(File::image('image.jpg'))
            ->toMediaCollection('featured');

        $this->mock(TwitterOAuth::class, function($mock) use ($path, $media) {
            $mock->shouldReceive('upload')
                ->with(
                    'media/upload',
                    [
                        'media' => "$path$media->id/image.jpg",
                    ]
                )
                ->andReturn([
                    'media_id_string' => '1',
                ])
                ->once();

            $mock->shouldReceive('post')
                ->with(
                    'statuses/update',
                    [
                        "status" => "This is a test tweet",
                        "response_format" => "json",
                        'media_ids' => '1',
                    ]
                )
                ->once();
        });

        $notification->sendAsTweet();
    }

    public function test_should_send_a_direct_message()
    {
        $this->mock(TwitterOAuth::class, fn($mock) =>
            $mock->shouldReceive('post')
                ->with(
                    'direct_messages/events/new',
                    [
                        'event' => [
                            'type' => 'message_create',
                            'message_create' => [
                                'target' => [
                                    'recipient_id' => '1234'
                                ],
                                'message_data' => [
                                    'text' => 'This is a test tweet'
                                ]
                            ]
                        ]
                    ],
                    true
                )
                ->once()
        );

        $notification = ScheduledNotification::factory()->create([
            'type' => TwitterChannel::class,
            'destination' => '1234',
            'message' => 'This is a test tweet',
            'sent' => false,
            'scheduled_at' => now(),
        ]);

        $notification->sendAsTweet();
    }
}
