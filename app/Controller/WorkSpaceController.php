<?php
namespace WorkSpace\Controller;
use WorkSpace\Service\WorkSpaceService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class WorkSpaceController
{
   private WorkSpaceService $WSService;

   public function __construct(WorkSpaceService $WSService)
   {
      $this->WSService = $WSService;
   }

   public function getWorkSpacesByIDUser(Request $request): JsonResponse
   {
      try {
         $IDUser = $request->attributes->get('USER')['IDUser'];
         $workspaces = $this->WSService->getAllWorkSpaces($IDUser);
         return new JsonResponse([
            'message' => 'WorkSpace List',
            'data' => $workspaces
         ], 200);
      } catch (Exception $e) {
         return new JsonResponse([
            'message' => "You don't have a WorkSpace yet, let create one! ",
         ], 400); 
      }
   }

   public function getWorkSpacesByIDWorkSpace(Request $request, string $IDWorkSpace): JsonResponse
   {
      try {
         $IDUser = $request->get('USER')['IDUser'];
         $workspaces = $this->WSService->getWorkSpaceByIDWS($IDWorkSpace, $IDUser);
         return new JsonResponse([
            'message' => 'WorkSpace List',
            'data' => $workspaces
         ], 200);
      } catch (Exception $e) {
         return new JsonResponse([
            'message' => "You don't have a WorkSpace yet, let create one! ",
         ], 400);
      }
   }

   public function createWorkSpace(Request $request): JsonResponse
   {
      try {
         $IDUser = $request->attributes->get('USER')['IDUser'];
         $workSpace = $this->WSService->createWorkSpace($request->json()->all(), $IDUser);
         return new JsonResponse([
            'message' => 'Created WorkSpace Successfully',
            'data' => $workSpace
         ], 201);
      } catch (Exception $e) {
         return new JsonResponse([
            'message' => $e->getMessage(),
         ], 400);
      }
   }
}