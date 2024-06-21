<?php

namespace Database\Factories;

use App\Enums\Region;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Conference;
use App\Models\Venue;

class ConferenceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Conference::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['upcoming', 'ongoing', 'past']);
        $region = $this->faker->randomElement(Region::class);
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'start_date' => $this->faker->dateTime(),
            'end_date' => $this->faker->dateTime(),
            'status' => $status,
            'is_published' => $status !== 'upcoming',
            'region' => $region,
            'venue_id' => Venue::factory()->state(['region' => $region]),
        ];
    }
}
