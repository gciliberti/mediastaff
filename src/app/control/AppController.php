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
        unset($_SESSION['idBorrower']);
        unset($_SESSION['listeEmprunt']);
        unset($_SESSION['listeReference']);
        $vue = new \app\view\AppView();
        $vue->render("home");

    }

    public function viewBorrow()
    {
        $vue = new \app\view\AppView();
        $http = new \mf\utils\HttpRequest();
        if (isset($http->post['numAdherent'])) {//si on vient de userBorrow
            $numAdherent = $http->post['numAdherent'];
            $countUser = \app\model\User::where('id', '=', $numAdherent)->count();
            if ($countUser != 1) {//L'utilisateur n'existe pas
                $vueErreur = new \app\view\AppView("<script>alert(\"L'utilisateur n'existe pas!\")</script>");
                $vueErreur->render("borrowUser");
                return;
            }
            $_SESSION['idBorrower'] = filter_var($http->post['numAdherent'], FILTER_SANITIZE_STRING);//On initialise les variables de session
            $_SESSION['listeEmprunt'] = array();
        } else {
            if (isset($_SESSION['idBorrower'])) {//si on vient de Borrow
                if (isset($http->post['mediaRef'])) {//si la ref a bien été remplie
                    $refMedia = $http->post['mediaRef'];
                    $countMediaDispo = \app\model\Media::where('reference', '=', $refMedia)->where('disponibility', '=', 1)->count();
                    if ($countMediaDispo == 1) {//si le media est disponible et existe
                        $_SESSION['listeEmprunt'][] = filter_var($http->post['mediaRef'], FILTER_SANITIZE_STRING);
                    }
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


    public function viewDoc()
    {
        $http = new \mf\utils\HttpRequest();
        if (isset($http->get['ref'])) {
            $reference = filter_var($http->get['ref'], FILTER_SANITIZE_STRING);
            $media = \app\model\Media::select('*')->where('reference', '=', $reference)->first();
        } else {
            $post = $this->request->post;
            $ref = filter_var($post['ref'], FILTER_SANITIZE_STRING);
            $media = \app\model\Media::select('*')->where('reference', '=', $ref)->first();
        }
        if ($media != null) {
            $vue = new \app\view\AppView($media);
            $vue->render("viewdoc");
        } else {
            $vue = new \app\view\AppView();
            $vue->render("home");
        }
    }

    public function modifyDoc()
    {
        $http = new \mf\utils\HttpRequest();
        $post = $this->request->post;

        $title = filter_var($post["title"], FILTER_SANITIZE_STRING);
        $type = filter_var($post["type"], FILTER_SANITIZE_STRING);
        $genre = filter_var($post["genre"], FILTER_SANITIZE_STRING);
        $description = filter_var($post["description"], FILTER_SANITIZE_STRING);
        $disponibility = filter_var($post["disponibility"], FILTER_SANITIZE_STRING);
        $keywords = filter_var($post["keywords"], FILTER_SANITIZE_STRING);

        if ($_FILES['fileToUpload']['tmp_name'] != "") {
            $picture = file_get_contents($_FILES['fileToUpload']['tmp_name']);
            $media = \app\model\Media::where('reference', '=', $http->get['ref'])->update(['picture' => $picture]);
        }

        $media = \app\model\Media::where('reference', '=', $http->get['ref'])->update(['title' => $title,
            'genre' => $genre, 'type' => $type, 'description' => $description,
            'keywords' => $keywords, 'disponibility' => $disponibility]);

        $mediaB = \app\model\Media::select('*')->where('reference', '=', $http->get['ref'])->first();
        $vue = new \app\view\AppView($mediaB);
        $vue->render("viewdoc");
    }

    public function suppDoc()
    {
        $http = new \mf\utils\HttpRequest();
        $route = new \mf\router\Router();
        $url = $route->urlFor('home');
        $media = \app\model\Media::where('reference', '=', $http->get['ref'])->delete();

        header('location: ' . $url);
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


    public function viewReturnSummary($iduser = null)
    {
        $vue = new \app\view\AppView($iduser);
        $vue->render("returnsummary");
    }

    public function checkBorrow()
    {
        $http = new \mf\utils\HttpRequest();
        $vue = new \app\view\AppView();
        $numAdherent = $_SESSION['idBorrower'];
        $countUser = \app\model\User::where('id', '=', $numAdherent)->count();

        if ($countUser != 1) {//L'utilisateur n'existe pas
            $vueErreur = new \app\view\AppView("<script>alert(\"L'utilisateur n'existe pas!\")</script>");
            $vueErreur->render("borrowUser");
            return;
        }
        foreach ($_SESSION['listeEmprunt'] as $emprunt) {
            $countMediaDispo = \app\model\Media::where('reference', '=', $emprunt)->where('disponibility', '=', 1)->count();
            if ($countMediaDispo != 1) {//si le media est déjà emprunté ou indisponible

            } else {
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
        }
        $this->viewBorrowSummary();
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


    public function viewBorrowSummary()
    {
        $iduser = $_SESSION['idBorrower'];
        $vue = new \app\view\AppView($iduser);
        $vue->render("borrowsummary");
        unset($_SESSION['idBorrower']);
        unset($_SESSION['listeEmprunt']);

    }

    public function checkReturn()
    {
        //Doit effectuer un retour en BDD et rediriger vers returnSummary
        try {
            $http = new \mf\utils\HttpRequest();
            if (isset($http->post['ajout_x'])) {
                if (isset($http->post['ref'])) {//Si la reference n'est pas vide
                    if (!isset($_SESSION['listeReference'])) {
                        $_SESSION['listeReference'] = array();
                    }
                    $refMedia = $http->post['ref'];
                    $countMediaDispo = \app\model\Media::where('reference', '=', $refMedia)->where('disponibility', '=', 2)->count();
                    if ($countMediaDispo == 1) {//si le media est emprunté et existe
                        $_SESSION['listeReference'][] = filter_var($http->post['ref'], FILTER_SANITIZE_STRING);//on ajoute la reference dans la var de session
                    }
                } else {//si la reference est vide
                    $vue = new \app\view\AppView();
                    $vue->render("return");
                    return;
                }
                $vue = new \app\view\AppView();
                $vue->render("return");
                return;
            }
            //si on a validé
            if (!isset($_SESSION['listeReference'])) {
                $vue = new \app\view\AppView("<script>alert(\"Aucune référence insérée\")</script>");
                $vue->render("return");
                return;
            }
            if (empty($_SESSION['listeReference'])) {
                $vue = new \app\view\AppView("<script>alert(\"Aucune référence insérée\")</script>");
                $vue->render("return");
                return;
            }

            foreach ($_SESSION['listeReference'] as $reference) {
                $reference = filter_var($reference, FILTER_SANITIZE_STRING);
                $media = \app\model\Media::where('reference', '=', $reference)->first();
                if ($media != null)//si le media existe
                {
                    $return = $media->borrownotreturned()->first();
                    if ($return != null) {
                        $iduser = $return->id_user;
                        $return->returned = 1;
                        $return->save();
                        $media->disponibility = 1;
                        $media->save();
                    } else {
                        $vue = new \app\view\AppView();
                        $vue->render("return");
                        return;
                    }
                }
            }
            unset($_SESSION['listeReference']);
            $this->viewReturnSummary($iduser);
        } catch (\Exception $e) {
            $vue = new \app\view\AppView($e);
            $vue->render("return");
        }
    }
}
