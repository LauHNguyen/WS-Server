<?php
namespace WorkSpace\Service;

use Exception;
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

    // Tạo một team mới
   public function createTeam($data, $IDLeader)
   {
      $requiredFields = [
         'teamName' => 'Team Name is required'
      ];

      foreach ($requiredFields as $field => $message) {
         if (empty($data[$field])) {
            throw new Exception($message);
         }
      }

      $team = $this->findTeam('TeamName', $data['teamName'], $IDLeader);
      if ($team) {
         throw new Exception('Team already exists');
      } else {
         return Team::create([
            "IDLeader" => $IDLeader,
            "TeamName" => $data["teamName"],
            "TeamSize" => 1,
            "TeamDescription" => $data["teamDescription"] ?? null,
         ]);
      }
   }

   // Tìm một team theo trường cụ thể
   public function findTeam($field, $value, $IDLeader)
   {
      return Team::where($field, $value)
         ->where('IDLeader', $IDLeader)
         ->where('IsDeleted', false)
         ->first();
   }
}