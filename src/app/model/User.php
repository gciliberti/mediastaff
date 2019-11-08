<?php
namespace app\model;

class User extends \Illuminate\Database\Eloquent\Model{
  protected $table ='user';
  protected $primaryKey = 'id';
  public $timestamps = false;

  public function borrows(){
    return $this->hasMany('app\model\Borrow','id_user');
  }
  public function borrownotreturned(){
    return $this->hasMany('app\model\Borrow','id_user')->where('returned','=','0');
  }

  public function borrowreturned(){
    return $this->hasMany('app\model\Borrow','id_user')->where('returned','=','1');
  }
}
 ?>
