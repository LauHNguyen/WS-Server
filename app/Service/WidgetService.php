<?php
namespace WorkSpace\Service;
use Exception;
use WorkSpace\Model\Widget;
use WorkSpace\Model\WorkSpace;


class WidgetService
{
    private $widget;
    private $workSpace;

    private $noteService;

    public function __construct(Widget $widget, WorkSpace $workSpace, NoteService $noteService)
    {
        $this->widget = $widget;
        $this->workSpace = $workSpace;
        $this->noteService = $noteService;
    }

    public function createWidget($data)
    {
        $requiredFields = [
            'IDWorkSpace' => 'IDWorkSpace is required',
            'WidgetType' => 'WidgetType is required',
            'Width' => 'Width is required',
            'Height' => 'Height is required',
            'Color' => 'Color is required',
            'PositionX' => 'PositionX is required',
            'PositionY' => 'PositionY is required',
        ];
        foreach ($requiredFields as $field => $message) {
            if (empty($data[$field])) {
                throw new Exception($message);
            }
        }
        $ws = $this->workSpace->find($data['IDWorkSpace']);
        if (!$ws) {
            throw new Exception('WorkSpace not found');
        }
        $maxZ_Index = $this->findMaxZ_Index($data["IDWorkSpace"]);
        $widget = Widget::create([
            "IDWorkSpace" => $data["IDWorkSpace"],
            "WidgetType" => $data["WidgetType"],
            "Z_Index" => $maxZ_Index + 1,
            "Width" => $data["Width"],
            "Height" => $data["Height"],
            "Color" => $data["Color"],
            "PositionX" => $data["PositionX"],
            "PositionY" => $data["PositionY"],
        ]);
        return $widget;
    }

    public function findWidget($field, $value)
    {
        return Widget::where($field, $value)
        ->where('IsDeleted', false)
        ->first();
    }

    public function findMaxZ_Index($IDWorkSpace)
    {
        $query = Widget::query();
        if ($IDWorkSpace) {
            $query->where('IDWorkSpace', $IDWorkSpace);
        }
        $maxZIndex = $query->max('Z_Index');
        return $maxZIndex !== null ? (int) $maxZIndex : 0;        
    }

    public function updateIDWidgetChild($IDWidget)
    {
        $widget = $this->widget::find($IDWidget);
        $note = $this->noteService->findNote('IDWidget',$IDWidget);
        $widget->update([
            "IDWidgetChild" => $note->IDNote,
        ]);
        $widget->save();
        $widget->refresh();
        return $widget;
    }

}
