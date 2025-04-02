<?php

namespace WorkSpace\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

use WorkSpace\Service\WidgetService;

class WidgetController
{
    private $widgetService;

    public function __construct(WidgetService $widgetService)
    {
        $this->widgetService = $widgetService;
    }

    public function GetAllWidgets($IDWorkSpace)
    {
        try {
            $allWidgets = $this->widgetService->GetAllWidgets($IDWorkSpace);
            return new JsonResponse([
                'message' => 'Get all widgets successfully',
                'data' => $allWidgets
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], 400);
        }
    }

    public function ModifyWidget(Request $request, $IDWorkSpace, $IDWidget)
    {
        try {
            $data = $request->json()->all();
            $response = $this->widgetService->ModifyWidget($data, $IDWorkSpace, $IDWidget);
            return new JsonResponse([
                'message' => 'Modify widget successfully',
                'data' => $response
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], 400);
        }
    }
}