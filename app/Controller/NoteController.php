<?php
namespace WorkSpace\Controller;
use WorkSpace\Service\NoteService;
use WorkSpace\Service\WidgetService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class NoteController 
{
   
   private NoteService $noteService;
   private WidgetService $widgetService;

   public function __construct(NoteService $noteService, WidgetService $widgetService)
   {
      $this->noteService = $noteService;
      $this->widgetService = $widgetService;
   }

   public function createNotewithWidget(Request $request)
   {
      try{
         $IDUser = $request->get('USER')['IDUser'];
         $widget = $this->widgetService->createWidget($request->json()->all());
         $note = $this->noteService->createNote($request->all(), $IDUser,$this->widgetService);
        
         if($note){
            $updatewidget = $this->widgetService->updateIDWidgetChild($note->IDWidget);
            return new JsonResponse([
               'message' => 'Created Note and Widget Successfully',
               'data' => $widget,$note,$updatewidget,
            ], 201);
         }
         else {
            // Xóa widget nếu note không được tạo
            $widget->delete();
            return new JsonResponse([
                'message' => 'Failed to create Note, Widget has been deleted',
            ], 400);
        }
      }catch(Exception $e){
         if (isset($widget)) {
            $widget->delete();
         }
         return new JsonResponse([
            'message' => $e->getMessage(),
         ], 400);
      }
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
            return new JsonResponse(['message' => $e->getMessage()], 400);
        }
    }
}