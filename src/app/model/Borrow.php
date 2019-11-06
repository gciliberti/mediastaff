<?php
namespace app\model;

class Borrow extends \Illuminate\Database\Eloquent\Model{
  protected $table ='borrow';
  protected $primaryKey = 'id';
  public $timestamps = false;

  public function media(){
    return $this->hasOne('app\model\Media','id');
  }
}
 ?>
