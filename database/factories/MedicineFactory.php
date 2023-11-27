<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medicine>
 */
class MedicineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $form = $this->faker->randomElement([ 'Wines', 'Pill' , 'Needle' , 'Suppository' , 'Drop' , 'Ointment' , 'Sprayer']);
        $start_date = $this->faker->date;
        $end_date = $this->faker->creditCardExpirationDate();
        $category =$this->faker->randomElement(['Sugar' , 'Salt' , 'Neurological' , 'Analgesic' , 'Joint' , 'Endocrine' , 'Dermatological']);

         return [
            'scientific_name' => $this->faker->name,
            'trade_name' =>$this->faker->name,
            'company_name' => $this->faker->company,
            'categories_name' => $category,
            'quantity'=>$this->faker->numberBetween(1 , 7000),
            'expiration_at' => $this->faker->dateTimeBetween($start_date = 'now' ,$end_date ),
            'price' =>$this->faker->numberBetween(2000 , 70000),
            'form' =>$form,
            'details' => $this->faker->realText($this->faker->numberBetween(10, 30)),
        ];
    }
}
