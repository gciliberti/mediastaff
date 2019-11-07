<?php

namespace app\control;

class AppController extends \mf\control\AbstractController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function viewHome()
    {
        $vue = new \app\view\AppView();
        $vue->render("home");

    }

    public function viewBorrow()
    {
        $vue = new \app\view\AppView();
        $http = new \mf\utils\HttpRequest();
        if (isset($http->post['numAdherent'])) {//si on vient de userBorrow
            $_SESSION['idBorrower'] = $http->post['numAdherent'];//On initialise les variables de session
            $_SESSION['listeEmprunt'] = array();
        } else {
            if (isset($_SESSION['idBorrower'])) {//si on vient de Borrow
                if (isset($http->post['mediaRef'])) {//si la ref a bien été remplie
                    $_SESSION['listeEmprunt'][] = $http->post['mediaRef'];
                    if (isset($http->post["valider"])) {//si on a validé on envoi sur checkBorrow
                        $this->checkBorrow();
                        return;
                    }
                }
            }
        }
        $vue->render("borrow");

    }

    public function viewBorrowUser()
    {
        $vue = new \app\view\AppView();
        $vue->render("borrowUser");
    }

    public function viewReturn()
    {
        $vue = new \app\view\AppView();
        $vue->render("return");

    }

    public function viewAddDoc()
    {
        $vue = new \app\view\AppView();
        $vue->render("adddoc");

    }

    public function viewUserRegister()
    {

        if (!empty($_GET['accept']) || !empty($_GET['delete'])) {
          
            \mf\router\Router::executeRoute('userModify');
        } else {
            $users = \app\model\User::where('isvalidated', '=', 0)->get();
            $vue = new \app\view\AppView($users);
            $vue->render("userregister");
        }

    }

    public function viewUserModify()
    {

        if (!empty($_GET['accept'])) {
            $user = \app\model\User::where('id', '=', $_GET['accept'])->first();
            $user->isvalidated = 1;
            $user->save();
            unset ($_GET['accept']);
            \mf\router\Router::executeRoute('users');
        } elseif (!empty($_GET['delete'])) {
            $user = \app\model\User::where('id', '=', $_GET['delete'])->first();
            $user->delete();

            unset ($_GET['delete']);
            \mf\router\Router::executeRoute('users');
        }
    }

    public function checkBorrow()
    {
        $http = new \mf\utils\HttpRequest();
        $vue = new \app\view\AppView();
        $numAdherent = $_SESSION['idBorrower'];
        $countUser = \app\model\User::where('id', '=', $numAdherent)->count();

        if ($countUser != 1) {//L'utilisateur n'existe pas
            $vue->render("borrowUser");
            return;
        }
        foreach ($_SESSION['listeEmprunt'] as $emprunt) {
            $countMedia = \app\model\Media::where('reference', '=', $emprunt)->count();
            if ($countMedia != 1) {//Le media n'existe pas
                $vue->render("borrow");
                return;
            }
            $mediaId = \app\model\Media::where('reference', '=', $emprunt)->first();
            $mediaId->disponibility = 2;//Le media est emprunté
            $borrow = new \app\model\Borrow();
            $dateDuJour = date("Y/m/d");
            $dateRendu = date('Y-m-d', strtotime($dateDuJour . ' + 14 days'));
            $borrow->borrow_date_end = $dateRendu;
            $borrow->returned = 0;
            $borrow->id_user = $numAdherent;
            $borrow->id_media = $mediaId->id;
            $borrow->save();
            $mediaId->save();
        }
        $vue->render("borrowSummary");
    }

    public function viewUserInfo()
    {
        $num = filter_var($_GET['num'], FILTER_SANITIZE_NUMBER_INT);
        $user = \app\model\User::where('id', '=', $num)->first();
        $vue = new \app\view\AppView($user);
        $vue->render("userinfo");
    }

    public function checkDoc()
    {
        $route = new \mf\router\Router();
        $url = $route->urlFor('home');
        $post = $this->request->post;

        $title = filter_var($post["title"], FILTER_SANITIZE_STRING);
        $ref = filter_var($post["ref"], FILTER_SANITIZE_STRING);
        $type = filter_var($post["type"], FILTER_SANITIZE_STRING);
        $genre = filter_var($post["genre"], FILTER_SANITIZE_STRING);
        $keywords = filter_var($post["keywords"], FILTER_SANITIZE_STRING);
        $description = filter_var($post["description"], FILTER_SANITIZE_STRING);
        $picture = file_get_contents($_FILES['fileToUpload']['tmp_name']);

        $testRef = \app\model\Media::where('reference', '=', $ref)->count();
        if ($testRef == 0) {
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


        header('location: ' . $url);
    }


    //Doit ajouter un doc en BDD (voir comment rediriger vers home après)

    public function viewBorrowSummary()
    {
        $vue = new \app\view\AppView();
        $vue->render("borrowsummary");

    }

    public function viewReturnSummary()
    {
        $vue = new \app\view\AppView();
        $vue->render("returnsummary");

    }


    public function checkReturn()
    {
        //Doit effectuer un retour en BDD et rediriger vers returnSummary
    }
}
