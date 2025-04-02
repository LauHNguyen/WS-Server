<?php
namespace WorkSpace\Service;
use Exception;
use WorkSpace\Model\Note;
use WorkSpace\Model\User;

class NoteService
{
    private $workSpace;
    private $noteModel;
    private $userModel;

    public function __construct(Note $noteModel, User $userModel)
    {
        $this->noteModel = $noteModel;
        $this->userModel = $userModel;
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

    //chỉnh sửa note
    public function ModifyNote($data, $IDUser, $IDNote)
    {
        try {

            $existUser = $this->userModel->where('IDUser', $IDUser)->first();
            if (!$existUser) {
                throw new Exception('User does not exist');
            }

            $updateNote = $this->noteModel->where('IDNote', $IDNote)->first();
            if (!$updateNote) {
                throw new Exception('Note does not exist');
            }

            if(!empty($data['Title'])){
                $updateNote->Title = $data['Title'];
            }
            else{
                throw new Exception('Title is not blank');
            }

            if(!empty($data['Content'])){
                $updateNote->Content = $data['Content'];
            }

            if($updateNote->Author != $IDUser){
                throw new Exception('You are not the author of this note');
            }

            if(isset($data['IsPublic'])){
                $updateNote->IsPublic = $data['IsPublic'];
            }

            if(!empty($data['Thumbnail'])){
                $updateNote->Thumbnail = $data['Thumbnail'];
            }
            
            $updateNote->save();

            return $updateNote;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}