<?php
namespace WorkSpace\Service;
use Exception;
use WorkSpace\Model\Widget;
use WorkSpace\Model\WorkSpace;

class WidgetService
{
    private $widgetModel;
    private $workSpaceModel;

    private $noteService;

    public function __construct(Widget $widgetModel, WorkSpace $workSpaceModel, NoteService $noteService)
    {
        $this->widgetModel = $widgetModel;
        $this->workSpaceModel = $workSpaceModel;
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
        $ws = $this->workSpaceModel->find($data['IDWorkSpace']);
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
        $widget = $this->widgetModel::find($IDWidget);
        $note = $this->noteService->findNote('IDWidget',$IDWidget);
        $widget->update([
            "IDWidgetChild" => $note->IDNote,
        ]);
        $widget->save();
        $widget->refresh();
        return $widget;
    }

    public function GetAllWidgets($IDWorkSpace)
    {
        try {
            $workSpace = $this->workSpaceModel->find($IDWorkSpace);
            if (empty($workSpace)) {
                throw new Exception("WorkSpace not found");
            }

            $allWidgets = $this->widgetModel
                ->where('IDWorkSpace', $IDWorkSpace)
                ->where('IsDeleted', false)
                ->get();
            return $allWidgets;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function ModifyWidget($data, $IDWorkSpace, $IDWidget){
        try {
            $workSpace = $this->workSpaceModel->find($IDWorkSpace);
            if (empty($workSpace)) {
                throw new Exception("WorkSpace not found");
            }

            $modifyWidget = $this->widgetModel->find($IDWidget);
            if (empty($modifyWidget)) {
                throw new Exception("Widget not found");
            }

            if(!empty($data['Z_Index'])){
                $modifyWidget->Z_Index = $data['Z_Index'];
            }

            if(!empty($data['Width'])){
                $modifyWidget->Width = $data['Width'];
            }

            if(!empty($data['Height'])){
                $modifyWidget->Height = $data['Height'];
            }

            if(!empty($data['Color'])){
                $modifyWidget->Color = $data['Color'];
            }

            if(!empty($data['PositionX'])){
                $modifyWidget->PositionX = $data['PositionX'];
            }

            if(!empty($data['PositionY'])){
                $modifyWidget->PositionY = $data['PositionY'];
            }

            $modifyWidget->save();

            return $modifyWidget;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
