<?php
namespace WorkSpace\Service;

use WorkSpace\Model\Team;
use WorkSpace\Model\TeamMember;

class TeamService
{
    protected $teamModel;
    protected $teamMemberModel;

    public function __construct(Team $teamModel, TeamMember $teamMemberModel)
    {
        $this->teamModel = $teamModel;
        $this->teamMemberModel = $teamMemberModel;
    }

    public function getAllTeams($IDUser)
    {
        $teamIds = $this->teamMemberModel
            ->where([
                ['IDUser', $IDUser],
                ['IsDeleted', false]
            ])
            ->pluck('IDTeam');

        $teams = Team::with('leader')
            ->where('IsDeleted', 0)
            ->whereIn('IDTeam', $teamIds)
            ->get();

        // Ẩn cột IDLeader khỏi response
        $teams->makeHidden('IDLeader');

        return $teams;
    }
}