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

  public function viewReturnSummary($iduser=null){
    $vue = new \app\view\AppView($iduser);
    $vue->render("returnsummary");

  }

  public function checkBorrow(){
    //Doit ajouter un nouvel emprunt et rediriger vers borrowsummary



  }


  public function checkDoc(){
    //Doit ajouter un doc en BDD (voir comment rediriger vers home après)

  }

  public function checkReturn(){
    //Doit effectuer un retour en BDD et rediriger vers returnSummary
    try{
      $http = new \mf\utils\HttpRequest();
      $reference = filter_var($http->post["ref"],FILTER_SANITIZE_STRING);
      $media =  \app\model\Media::where('reference','=',$reference)->first();
      if($media != null)
      {
          $return = $media->borrownotreturned()->first();
          if($return != null)
          {
            $iduser = $return->id_user;
            $return->returned = 1;
            $return->save();
            $this->viewReturnSummary($iduser);
          }
          else{
            $vue = new \app\view\AppView();
            $vue->render("return");
          }
      }
      else{
        $vue = new \app\view\AppView();
        $vue->render("return");
      }

    }
    catch(\Exception $e)
    {

      $vue = new \app\view\AppView($e);
      $vue->render("return");
    }

  }
}
