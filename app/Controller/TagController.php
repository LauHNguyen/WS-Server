<?php
namespace WorkSpace\Controller;
use WorkSpace\Service\TagService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class TagController
{
   private TagService $tagService;

   public function __construct(TagService $tagService)
   {
      $this->tagService = $tagService;
   }
   public function createTag(Request $request): JsonResponse
   {
      try {
         $tag = $this->tagService->createTag($request->json()->all());
         return new JsonResponse([
            'message' => 'Created Tag Successfully',
            'data' => $tag
         ], 201);
      } catch (Exception $e) {
         return new JsonResponse([
            'message' => $e->getMessage(),
         ], 400);
      }
   }
}