<?php
namespace WorkSpace\Model;

class Widget extends Entity
{
   protected $table = 'WIDGET';
   protected $primaryKey = 'IDWidget';
   protected $fillable = ['IDWorkSpace', 'WidgetType', 'Z_Index', 'Width', 'Height', 'Color', 'PositionX', 'PositionY', 'IsDeleted', 'IDWidgetChild'];
   protected $attributes = [
      'WidgetType' => 'Note'
   ];

   public function workspace()
   {
      return $this->belongsTo(Workspace::class, 'IDWorkSpace', 'IDWorkSpace');
   }

   public function notes()
   {
      return $this->hasMany(Note::class, 'IDWidget', 'IDWidget');
   }
}