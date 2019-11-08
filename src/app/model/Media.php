<?php
namespace app\model;

class Media extends \Illuminate\Database\Eloquent\Model{
  protected $table ='media';
  protected $primaryKey = 'id';
  public $timestamps = false;

  public function borrownotreturned(){
    return $this->hasMany('app\model\Borrow','id_media')->where('returned','=','0');
  }

  public function borrowreturned(){
    return $this->hasMany('app\model\Borrow','id_media')->where('returned','=','1');
  }

}
 ?>
