<?php
namespace WorkSpace\Service;
use Exception;
use WorkSpace\Model\Note;

class NoteService
{
    private $widget;
    private $workSpace;

    private $note;

    public function __construct(Note $note)
    {
        $this->note = $note;
        // $this->widget = $widget;
    }

    public function createNote($data, $IDUser,  WidgetService $widgetService)
    {
        $requiredFields = [
            'Title' => 'Title is required',
            'Content' => 'Content is required',
        ];

        foreach ($requiredFields as $field => $message) {
            if (empty($data[$field])) {
                throw new Exception($message);
            }
        }
        //Lấy Z-Index từ widget mới tạo
        $maxZ_Index = $widgetService->findMaxZ_Index($data["IDWorkSpace"]);
        $Widget = $widgetService->findWidget('Z_Index', $maxZ_Index);


        try {
            return Note::create([
                "IDWidget" => $Widget->IDWidget,
                "Author" => $IDUser,
                "CreatedAt" => date('Y-m-d H:i:s'),
                "Title" => $data["Title"],
                "Content" => $data["Content"],
            ]);
        } catch (Exception $e) {
            throw new Exception('Failed to create Note: ' . $e->getMessage());
        }
    }

    public function findNote($field, $value)
    {
        return Note::where($field, $value)
        ->where('IsDeleted', false)
        ->first();
    }
}