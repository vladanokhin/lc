<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'url'          => $this->faker->url(),
            'geo'          => $this->faker->stateAbbr(),
            'language'     => $this->faker->word(),
            'type'         => $this->faker->word(),
            'category'     => $this->faker->word(),
            'form_factor'  => $this->faker->word(),
            'lp_numbering' => $this->faker->randomNumber(3),
            'name'         => $this->faker->userName(),
            'aff_network'  => $this->faker->word(),
            'price'        => $this->faker->randomNumber(3),
            'offer_type'   => $this->faker->word(),
        ];
    }
}
