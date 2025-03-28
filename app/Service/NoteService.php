<?php
namespace WorkSpace\Service;
use Exception;
use WorkSpace\Model\Note;
use WorkSpace\Model\User;

class NoteService
{
    private $noteModel;
    private $userModel;

    public function __construct(Note $noteModel, User $userModel)
    {
        $this->noteModel = $noteModel;
        $this->userModel = $userModel;
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