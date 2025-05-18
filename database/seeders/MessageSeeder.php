<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;
use Faker\Factory as Faker;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) {
            $sender = $faker->numberBetween(1, 5);
            $receiver = $faker->numberBetween(1, 5);
            while ($sender == $receiver) {
                $receiver = $faker->numberBetween(1, 5);
            }

            Message::create([
                'sender_id' => $sender,
                'receiver_id' => $receiver,
                'message_content' => $faker->sentence(),
            ]);
        }
    }
}
