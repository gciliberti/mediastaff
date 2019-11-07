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
    $vue = new \app\view\AppView();
    $vue->render("borrow");
  }

  public function viewReturn(){
    $vue = new \app\view\AppView();
    $vue->render("return");

  }

  public function viewAddDoc(){
    $vue = new \app\view\AppView();
    $vue->render("adddoc");

  }

  public function viewUserRegister(){
    $vue = new \app\view\AppView();
    $vue->render("userregister");

  }

  public function viewUserInfo(){
    $vue = new \app\view\AppView();
    $vue->render("userinfo");

  }

  public function viewBorrowSummary(){
    $vue = new \app\view\AppView();
    $vue->render("borrowsummary");

  }

  public function viewReturnSummary(){
    $vue = new \app\view\AppView();
    $vue->render("returnsummary");

  }

  public function checkBorrow(){

  }

  public function checkSummary(){

  }

  public function checkDoc(){

  }
}
