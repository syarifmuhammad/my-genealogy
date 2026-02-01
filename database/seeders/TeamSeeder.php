<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Laravel\Jetstream\Jetstream;

final class TeamSeeder extends Seeder
{
    public function run(): void
    {
        
    }

    // -----------------------------------------------------------------------------------
    protected function createTeam(string $email, string $name, ?string $description = null): Team
    {
        $user = Jetstream::findUserByEmailOrFail($email);

        $team = Team::forceCreate([
            'user_id'       => $user->id,
            'name'          => $name,
            'description'   => $description,
            'personal_team' => false,
        ]);

        $user->ownedTeams()->save($team);

        return $team;
    }

    // -----------------------------------------------------------------------------------
    // helper to attach + update current_team_id
    // -----------------------------------------------------------------------------------
    protected function assignUserToTeam(User $user, Team $team, string $role): void
    {
        $team->users()->syncWithoutDetaching([
            Jetstream::findUserByEmailOrFail($user->email)->id => ['role' => $role],
        ]);

        $user->update(['current_team_id' => $team->id]);
    }
}
