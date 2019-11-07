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
    $http = new \mf\utils\HttpRequest();
    if(isset($http->post['numAdherent'])){//si on vient de userBorrow
      $_SESSION['idBorrower']=$http->post['numAdherent'];//On initialise les variables de session
      $_SESSION['listeEmprunt']=array();
    }else{
      if(isset($_SESSION['idBorrower'])){//si on vient de Borrow
        if(isset($http->post['mediaRef'])){//si la ref a bien été remplie
          $_SESSION['listeEmprunt'][] = $http->post['mediaRef'];
          if(isset($http->post["valider"])){//si on a validé on envoi sur checkBorrow
            $this->checkBorrow();
            return;
          }
        }
      }
    }
    $vue->render("borrow");

  }

  public function viewBorrowUser(){
    $vue = new \app\view\AppView();
    $vue->render("borrowUser");
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
    $http = new \mf\utils\HttpRequest();
    $vue = new \app\view\AppView();
    $numAdherent=$_SESSION['idBorrower'];
    $countUser=\app\model\User::where('id','=',$numAdherent)->count();

    if($countUser!=1){//L'utilisateur n'existe pas
      $vue->render("borrowUser");
      return;
    }
    foreach ($_SESSION['listeEmprunt'] as $emprunt) {
      $countMedia=\app\model\Media::where('reference', '=', $emprunt)->count();
      if($countMedia!=1){//Le media n'existe pas
        $vue->render("borrow");
        return;
      }
      $mediaId = \app\model\Media::where('reference', '=', $emprunt)->first();
      $mediaId->disponibility = 2;//Le media est emprunté
      $borrow = new \app\model\Borrow();
      $dateDuJour=date("Y/m/d");
      $dateRendu = date('Y-m-d', strtotime($dateDuJour. ' + 14 days'));
      $borrow->borrow_date_end=$dateRendu;
      $borrow->returned=0;
      $borrow->id_user=$numAdherent;
      $borrow->id_media=$mediaId->id;
      $borrow->save();
      $mediaId->save();
    }
    $vue->render("borrowSummary");
  }


  public function checkDoc(){
    //Doit ajouter un doc en BDD (voir comment rediriger vers home après)

  }

  public function checkReturn(){
    //Doit effectuer un retour en BDD et rediriger vers returnSummary
  }
}
