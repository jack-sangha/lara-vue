<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Factories bypass $fillable, so role can be set without making it mass-assignable.
        $agents = collect([1, 2])->map(fn($i) => User::factory()->create(
            ['name' => "Agent $i", 'email' => "agent$i@example.com", 'role' => 'agent']
        ));
        $customers = collect(range(1, 5))->map(fn($i) => User::factory()->create(
            ['name' => "Customer $i", 'email' => "customer$i@example.com", 'role' => 'customer']
        ));

        Ticket::factory(50)
            ->state(fn() => [
                'created_by' => $customers->random()->id,
                'assigned_to' => fake()->boolean(60) ? $agents->random()->id : null,
            ])
            ->create();
    }
}
