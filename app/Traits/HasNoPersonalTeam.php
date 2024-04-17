<?php

namespace App\Traits;

trait HasNoPersonalTeam
{
    public function ownsTeam($team): bool
    {
        return $this->id == optional($team)->user_id;
    }

    /**
     * Determine if the given team is the current team.
     *
     * @param  mixed  $team
     */
    public function isCurrentTeam($team): bool
    {
        return optional($team)->id === $this->currentTeam->id;
    }

    /**
     * Determine if the user is apart of any team.
     */
    public function isMemberOfATeam(): bool
    {
        return (bool) ($this->teams()->count() || $this->ownedTeams()->count());
    }
}
