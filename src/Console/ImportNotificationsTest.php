<?php

namespace Helious\SeatNotificationsPlus\Console;

use Illuminate\Console\Command;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ImportNotificationsTest extends Command
{
    protected $signature = 'test:import-notif';

    protected $description = 'Imports notifications from a JSON file for testing purposes';

    public function handle()
    {
        // Path to the JSON file within your Laravel storage directory
        $path = base_path('vendor/helious/seat-notifications-plus/src/Tests/notifications.json');
        $this->info('Importing notifications from ' . $path . '...');

        // Load and decode the JSON file
        $json = json_decode(file_get_contents($path), true);

        // Check if JSON decoding was successful
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Failed to decode JSON: ' . json_last_error_msg());
            return;
        }

        // Iterate through each notification and insert into the database
        foreach ($json as $notificationData) {
            $this->info('Importing notification ' . $notificationData['type'] . '...');
            CharacterNotification::create([
                'character_id' => $notificationData['character_id'],
                'notification_id' => $notificationData['notification_id'],
                'type' => $notificationData['type'],
                'sender_id' => $notificationData['sender_id'],
                'sender_type' => $notificationData['sender_type'],
                'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
                'text' => $notificationData['text']
            ]);
            sleep(1);
        }

        $this->info('Notifications imported successfully.');
    }
}
