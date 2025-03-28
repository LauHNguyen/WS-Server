<?php

namespace WorkSpace\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use WorkSpace\Service\NoteService;

class NoteController{
    private $noteService;

    public function __construct(NoteService $noteService){
        $this->noteService = $noteService;
    }

    public function ModifyNote(Request $request, $IDNote){
        try {
            $IDUser = $request->attributes->get('USER')['IDUser'];
            $data = $request->json()->all();
            $response = $this->noteService->ModifyNote($data, $IDUser, $IDNote);
            return new JsonResponse([
                'message' => 'Modify Note Successfully',
                'data' => $response
            ], );
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}