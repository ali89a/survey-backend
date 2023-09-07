<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $title = 0;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->title = $this->title+1;
        $status = $this->faker->randomElement(['open', 'in-progress', 'done']);
        return [
            'title'=>'Task-'.$this->title,
            'created_by'=>User::all()->random()->id,
            'assigned_to'=>$status !== 'open'? User::all()->random()->id : null,
            'assigned_by'=>$status !== 'open'? User::all()->random()->id : null,
            'description'=> $this->faker->realText(150, 2),
            'deadline'=>$this->faker->date(),
            'status'=>$status
        ];
    }
}
