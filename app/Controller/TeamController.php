<?php
namespace WorkSpace\Controller;

use WorkSpace\Service\TeamService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
class TeamController
{
    protected $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    public function getTeamsByIDUser(Request $request): JsonResponse
    {
        try {
            $IDUser = $request->attributes->get('USER')['IDUser'];
            $teams = $this->teamService->getAllTeams($IDUser);
            
            return new JsonResponse([
                'message' => 'Team List',
                'data' => $teams
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}