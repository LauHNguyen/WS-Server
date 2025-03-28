<?php

namespace WorkSpace\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use WorkSpace\Service\ProjectService;

class ProjectController
{
    private ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function createNewProject(Request $request): JsonResponse
    {
        try {
            $NewProject = $this->projectService->createNewProject($request->json()->all());
            return new JsonResponse([
                'message' => 'Created New Project Successfully',
                'data' => $NewProject
            ], 201);
        } catch (Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 400);
        }
    }
}