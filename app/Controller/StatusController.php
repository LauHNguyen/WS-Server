<?php
namespace WorkSpace\Controller;

use WorkSpace\Service\StatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class StatusController 
{
    private $statusService;

    public function __construct(StatusService $statusService)
    {
        $this->statusService = $statusService;
    }

    public function getAllStatuses(Request $request)
    {
        try {
            $IDUser = $request->attributes->get('USER')['IDUser'];
            $projectId = $request->query('project_id');
            $statuses = $this->statusService->getAllStatuses($projectId)->makeHidden('IDProject');

            return new JsonResponse([
                'message' => 'Status List',
                'IDProject' => $statuses[0]->IDProject ?? null,  
                'data' => $statuses
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function createStatus(Request $request)
    {
        try {
            $IDUser = $request->attributes->get('USER')['IDUser'];
            $data = $request->all(); // Lấy tất cả dữ liệu từ request

            $status = $this->statusService->createStatus($data, $IDUser);

            return new JsonResponse([
                'message' => 'Status created successfully',
                'data' => $status
            ], 201);
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}