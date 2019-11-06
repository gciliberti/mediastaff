<?php

namespace app\control;

class AppController extends \mf\control\AbstractController {
  public function __construct(){
    parent::__construct();
  }

  public function viewHome(){
    $vue = new \app\view\AppView();
    $vue->render("home");

  }

  public function viewBorrow(){

  }

  public function viewReturn(){

  }

  public function viewAddDoc(){

  }

  public function viewUserRegister(){

  }

  public function viewUserInfo(){

  }

  public function viewBorrowSummary(){

  }

  public function viewReturnSummary(){

  }
}
