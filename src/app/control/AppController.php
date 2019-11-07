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
        $vue->render("borrow");
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
        }
        $users = \app\model\User::where('isvalidated', '=', 0)->get();
        $vue = new \app\view\AppView($users);
        $vue->render("userregister");


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


    public function viewUserInfo()
    {
        $num = filter_var($_GET['num'], FILTER_SANITIZE_NUMBER_INT);
        $user = \app\model\User::where('id', '=', $num)->first();
        $vue = new \app\view\AppView($user);
        $vue->render("userinfo");

    }

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

    public function checkBorrow()
    {
        //Doit ajouter un nouvel emprunt et rediriger vers borrowsummary

    }


    public function checkDoc()
    {
        //Doit ajouter un doc en BDD (voir comment rediriger vers home après)

    }

    public function checkReturn()
    {
        //Doit effectuer un retour en BDD et rediriger vers returnSummary
    }
}
