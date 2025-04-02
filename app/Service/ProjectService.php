<?php
namespace WorkSpace\Service;
use Exception;
use WorkSpace\Model\Project;
use WorkSpace\Model\Team;

class ProjectService
{
    private $project;
    private $team;

    public function __construct(Project $project, Team $team)
    {
        $this->project = $project;
        $this->team = $team;
    }

    //tạo mới 1 project
    public function createNewProject($data)
    {
        $requiredFields = [
            "ProjectName" => "Project name is not blank"
        ];

        foreach ($requiredFields as $field => $message) {
            if (empty($data[$field])) {
                throw new Exception($message);
            }
        }

        $existTeam = Team::where('IDTeam', $data['IDTeam'])
            ->where('IsDeleted', false)
            ->first();

        if (!$existTeam) {
            throw new Exception('Team does not exist');
        }

        $existProject = Project::where('ProjectName', $data['ProjectName'])
            ->where('IDTeam', $data['IDTeam'])
            ->where('IsDeleted', false)
            ->first();

        if ($existProject) {
            throw new Exception('Project already exists');
        } else {
            return $this->project->create([
                "IDTeam" => $data["IDTeam"],
                "ProjectName" => $data["ProjectName"],
                "ProjectDescription" => $data["ProjectDescription"],
            ]);
        }
    }
}