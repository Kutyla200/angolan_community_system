<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    protected $model = \App\Models\Member::class;

    public function definition()
    {
        $provinces = [
            'Gauteng', 'Western Cape', 'KwaZulu-Natal', 'Eastern Cape',
            'Free State', 'Limpopo', 'Mpumalanga', 'North West', 'Northern Cape'
        ];

        $cities = [
            'Gauteng' => ['Johannesburg', 'Pretoria', 'Soweto', 'Randburg'],
            'Western Cape' => ['Cape Town', 'Stellenbosch', 'Paarl', 'Worcester'],
            'KwaZulu-Natal' => ['Durban', 'Pietermaritzburg', 'Richards Bay', 'Newcastle'],
            'Eastern Cape' => ['Port Elizabeth', 'East London', 'Grahamstown', 'Queenstown'],
        ];

        $province = $this->faker->randomElement($provinces);

        $city = isset($cities[$province])
            ? $this->faker->randomElement($cities[$province])
            : 'Other City';

        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'date_of_birth' => $this->faker->date('Y-m-d', '-30 years'),
            'nationality' => 'Angolan',
            'citizenship_status' => $this->faker->randomElement([
                'angolan', 'south_african', 'dual_citizenship'
            ]),
            'province' => $province,
            'city' => $city,
            'mobile_number' => '+27' . $this->faker->numerify('##########'),
            'email' => $this->faker->unique()->safeEmail(),
            'preferred_contact_method' => $this->faker->randomElement([
                'phone', 'whatsapp', 'email'
            ]),
            'employment_status' => $this->faker->randomElement([
                'employed', 'self_employed', 'student', 'unemployed'
            ]),
            'profession' => $this->faker->jobTitle(),
            'willing_to_help' => $this->faker->boolean(70),
            'consent_given' => true,
            'consent_given_at' => now(),
            'language_preference' => $this->faker->randomElement(['en', 'pt']),
            'registered_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
