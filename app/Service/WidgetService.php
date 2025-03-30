<?php
namespace WorkSpace\Service;
use Exception;
use WorkSpace\Model\Widget;
use WorkSpace\Model\WorkSpace;

class WidgetService
{
    private $widgetModel;
    private $workSpaceModel;

    public function __construct(Widget $widgetModel, WorkSpace $workSpaceModel)
    {
        $this->widgetModel = $widgetModel;
        $this->workSpaceModel = $workSpaceModel;
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
