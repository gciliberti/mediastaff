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
    //Doit ajouter un nouvel emprunt et rediriger vers borrowsummary

  }


  public function checkDoc(){
    $route = new \mf\router\Router();
    $url = $route->urlFor('home');
    $post = $this->request->post;

    $title = filter_var($post["title"],FILTER_SANITIZE_STRING);
    $ref = filter_var($post["ref"],FILTER_SANITIZE_STRING);
    $type = filter_var($post["type"],FILTER_SANITIZE_STRING);
    $genre = filter_var($post["genre"],FILTER_SANITIZE_STRING);
    $keywords = filter_var($post["keywords"],FILTER_SANITIZE_STRING);
    $description = filter_var($post["description"],FILTER_SANITIZE_STRING);
    $picture = file_get_contents($_FILES['fileToUpload']['tmp_name']);

    $testRef = \app\model\Media::where('reference', '=', $ref)->count();
    if($testRef == 0){
      $media = new \app\model\Media();
      $media->title = $title;
      $media->reference = $ref;
      $media->genre = $genre;
      $media->type = $type;
      $media->keywords = $keywords;
      $media->description = $description;
      $media->picture = $picture;
      $media->disponibility = 1;
      $media->save();
    } else {
      //echo "reference deja existante";
    }


    header('location: '.$url);


    //Doit ajouter un doc en BDD (voir comment rediriger vers home après)

  }

  public function checkReturn(){
    //Doit effectuer un retour en BDD et rediriger vers returnSummary
  }
}
