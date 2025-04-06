<?php
namespace WorkSpace\Service;
use Exception;
use WorkSpace\Model\Tag;

class TagService
{
   private $tagService;

   public function __construct(Tag $tagService)
   {
      $this->tagService = $tagService;
   }

   public function getAllTagsinProject($IDProject)
   {
      return Tag::select("IDTag", "TagName")
         ->where([
            ['IDProject', $IDProject],
            ['IsDeleted', false]
         ])->get();
   }

   public function createTag($data)
   {
      $requiredFields =
      [
         'IDProject' => 'IDProject is required',
         'TagName' => 'TagName is required',
      ];

      foreach ($requiredFields as $field => $message) {
         if (empty($data[$field])) {
            throw new Exception($message);
         }
      }
      $tag = $this->findTag('TagName', $data['TagName'], $data['IDProject']);
      if($tag){
         throw new Exception('Tag already exists in this project');
      } else {
         return Tag::create([
            "IDProject" => $data["IDProject"],
            "TagName" => $data["TagName"],
         ]);
      }
   }

   public function findTag($field, $value, $IDProject)
   {
      return Tag::where($field, $value)
         ->where('IDProject', $IDProject)
         ->where('IsDeleted', false)
         ->first();
   }
}