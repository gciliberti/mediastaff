<?php

namespace app\view;

use mf\router\Router;

class AppView extends \mf\view\AbstractView
{

    private function renderHeader()
    {
        $html = "";
        $app_root = (new \mf\utils\HttpRequest())->root;//Pour aller chercher les images
        $objRout = new \mf\router\Router();
        $hrefRetour = $objRout->urlFor('home');
        $html .= <<<EOT
      <nav>
        <a href="${hrefRetour}" class="back">
          <img src="${app_root}/html/img/back.svg" width="32" height="32" alt="fleche de retour">
        </a>
      </nav>
EOT;
        return $html;
    }

    private function renderFooter()
    {

    }

    public function renderHome()
    {

        $app_root = (new \mf\utils\HttpRequest())->root;//Pour aller chercher les images
        $route = new \mf\router\Router();
        $hrefBorrowUser = $route->urlFor('borrowUser');
        $hrefAddDoc = $route->urlFor('addDoc');
        $hrefReturn = $route->urlFor('return');
        $hrefUsers = $route->urlFor('users');


        $html = "";
        $html .= <<< EOT
      <main id="home">
          <nav>
              <div class="grid_container">
                  <a href="${hrefBorrowUser}">
                      <div class="item__nav">
                          <img src="${app_root}/html/img/books-stack.svg" alt="icone emprunt">
                          <h2>Emprunt</h2>
                      </div>
                  </a>
                  <a href="${hrefReturn}">
                      <div class="item__nav">
                          <img src="${app_root}/html/img/back.svg" alt="icone retour">
                          <h2>Retour</h2>
                      </div>
                  </a>
                  <a href="${hrefUsers}">
                      <div class="item__nav">
                          <img src="${app_root}/html/img/avatar.svg" alt="icone utilisateurs">
                          <h2>Utilisateurs</h2>
                      </div>
                  </a>
              </div>
              <a href="${hrefAddDoc}" class="adddoc"> <img src="${app_root}/html/img/button-plus.svg" alt="plus"> Ajouter un document</a>
          </nav>
      </main>
EOT;
        return $html;
    }

    public function renderReturn()
    {
        $router = new \mf\router\Router();
        $hrefCheckReturn = $router->urlFor('checkReturn');
        $html = "";
        $html .= <<<EOT
      <main id="return">
          <form action="${hrefCheckReturn}" method="post" name="return">
              <input type="text" name="ref" placeholder="Référence">
              <button type="submit">Valider</button>
          </form>
      </main>
EOT;
        return $html;
    }

    public function renderBorrowUser()
    {
        $router = new \mf\router\Router();
        $hrefCheckBorrow = $router->urlFor('borrow');
        $app_root = (new \mf\utils\HttpRequest())->root;

        $html = "";
        $html .= <<<EOT
      <main id="borrow">
          <form method="POST" action="${hrefCheckBorrow}" name="userBorrow">
              <div class="container userBorrow">
                <div class="item userBorrow">
                  <input type="text" name="numAdherent" placeholder="Numéro d'adhérent" required>
                </div>
                <div class="item userBorrow">
                  <button type="submit">Valider</button>
                </div>
              </div>
          </form>
      </main>
EOT;
        return $html;
    }

    public function renderBorrow()
    {
        $router = new \mf\router\Router();
        $hrefBorrow = $router->urlFor('borrow');
        $app_root = (new \mf\utils\HttpRequest())->root;
        $listeEmprunt = "";
        if (!empty($_SESSION['listeEmprunt'])) {
            $listeEmprunt = "<h4>Reference déjà ajoutée:</h2>";
            foreach ($_SESSION['listeEmprunt'] as $emprunt) {
                $listeEmprunt .= "<li>" . $emprunt . "</li>";
            }
        }
        $html = "";
        $html .= <<<EOT
      <main id="borrow">
          <form method="POST" action="${hrefBorrow}" name="borrow">
              <div class="container borrow">
                  <div class="item borrow">
                      <input required name="mediaRef" type="text" placeholder="Référence du document">
                      <input required name="Ajout" type="image" src="${app_root}/html/img/button-plus.svg" width="32" height="32" alt="Ajout"/>
                      <ul>
                        ${listeEmprunt}
                      </ul>
                  </div>
              </div>
              <div class="item borrow">
                  <button name="valider" type="submit">Valider</button>
              </div>
          </form>
      </main>
EOT;
        return $html;
    }

    private function renderAddDoc()
    {
        $router = new \mf\router\Router();
        $hrefCheckDoc = $router->urlFor('checkDoc');
        //je n'ai pas le html pour celle la
        $html = "";
        $html .= <<<EOT
      <main id="addDoc">
          <form method="post" action="${hrefCheckDoc}" name="addDoc" enctype="multipart/form-data">
              <div class="container addDoc">
                <input required id="title" name="title" value="" placeholder="titre">
                <input required id="ref" name="ref" value="" placeholder="référence">
                <input required id="type" name="type" value="" placeholder="type">
                <input required id="genre" name="genre" value="" placeholder="genre">
                <input required id="keywords" name="keywords" value="" placeholder="mots clefs">
                <div class="picture">
                  <label for="file" class="label-file">ajouter une image</label>
                  <input required id="file" type="file" name="fileToUpload">
                </div>
                <textarea required id="description" name="description" value="" placeholder="déscription"></textarea>
                <button type="submit">Valider</button>
              </div>
          </form>
      </main>

EOT;
        return $html;
    }

    private function renderUserRegister()
    {
        $html = "";
        $users = $this->data;
        $router = new \mf\router\Router();
        $hrefViewUser = $router->urlFor('viewUser');


        $html .= <<<EOT
            <main id="users">
                <div class="container users" style="border-bottom: 2px solid black">
EOT;


        foreach ($users as $value) {
            $surname = $value->surname;
            $name = $value->name;
            $num = $value->id;

            $app_root = (new \mf\utils\HttpRequest())->root;
            $picture = $value->photo;
            if (empty($picture)) {
                $picture = $app_root . "/html/img/avatar.svg";
            } else {
                $picture = "data:image/jpeg;base64," . base64_encode($value->photo);
            }

            $html .= <<<EOT
             <div class="item__user__container">
                  <img src="${picture}" alt="photo de profil" width="50px" height="auto">
                  <p>Adherent numéro : ${num}</p>
                  <p>${name} ${surname}</p>
                  <form name="validate" method="get">
                      <button type="submit" name="accept" value="${num}">
                      <img src="${app_root}/html/img/success.svg" alt=check" width="50px" height="auto">
                      </button>
                      <button type="submit" name="delete" value="${num}">
                      <img src="${app_root}/html/img/error.svg" alt=cross" width="50px" height="auto">
                      </button>
                  </form>
              </div>
EOT;
        }
        $html .= <<<EOT
          </div>

          <div class="flex_container">
              <nav>
                  <a href="">Créer un adhérent</a>
              </nav>
              <div class="item">
                  <h3>Informations sur un adhérent :</h3>
                  <form class="flex_container" action="${hrefViewUser}" id="info_adherent" method="get">
                      <input type="number" placeholder="Numéro d'adhérent" name="num">
                      <button type="submit">ok</button>
                  </form>
              </div>
          </div>
      </main>
EOT;
        return $html;
    }

    private function renderUserInfo()
    {

        $html = "";
        $html .= <<<EOT
      <main id="profil_user">
EOT;
        $user = $this->data;
        $num = $_GET['num'];
        if ($user != null) {
            $name = $user->name;
            $surname = $user->surname;
            $username = $user->username;
            $mail = $user->mail;
            $adresse = $user->address;
            $ville = $user->city;
            $postalcode = $user->postalcode;
            $tel = $user->phone;
            $html .= <<<EOT
           <div class="info">
              <ul>
                  <li>${name} ${surname}</li>
                  <li>${username}</li>
                  <li>${mail}</li>
              </ul>
          </div>
          <div class="where">
              <ul>
                  <li>${adresse}</li>
                  <li>${postalcode} ${ville}</li>
                  <li>${tel}</li>
              </ul>
          </div>
EOT;

        } else {
            $html .= <<<EOT
           <div class="error">
                <p>Attention l'adhérent numéro ${num} n'éxiste pas !</p>

           </div>
EOT;
        }

        $html .= <<<EOT

      </main>
EOT;
        return $html;
    }

    private function renderBorrowSummary()
    {
        $iduser = $this->data;
        $router = new \mf\router\Router();
        $hrefHome = $router->urlFor('home');
        $iduser = $this->data;
        $user = \app\model\User::where('id','=',$iduser)->first();

        $possessed = $user->borrownotreturned()->get();
        $name = $user->name;
        $surname = $user->surname;
        $possessedborrows = '';
        foreach($possessed as $borrow)
        {
          $title="";
          $date = $borrow->borrow_date_end;
          $borrow = $borrow->media()->get();
          foreach($borrow as $media)
          {
            $title = $media->title;
          }


          $possessedborrows .= <<< EOT
          <ul class="flex_container">
              <li>${title}</li>
              <li>A rendre le ${date}</li>
          </ul>
EOT;
        }
        $html = "";
        $html .= <<<EOT
      <main id="recap_borrow">
          <div>
              <h3>Adhérent n° ${iduser}, ${name} ${surname} </h3>
                ${possessedborrows}
          </div>
          <nav>
              <a href="${hrefHome}" id="nav_recap_borrow">ok</a>
          </nav>
      </main>
EOT;
        return $html;
    }

    private function renderReturnSummary(){
      $iduser = $this->data;
      $user = \app\model\User::where('id','=',$iduser)->first();

      $possessed = $user->borrownotreturned()->get();
      $returned = $user->borrowreturned()->get();
      $name = $user->name;
      $surname = $user->surname;
      $router = new \mf\router\Router();
      $hrefHome = $router->urlFor('home');
      $possessedborrows = '';
      foreach($possessed as $borrow)
      {
        $title="";
        $date = $borrow->borrow_date_end;
        $borrow = $borrow->media()->get();
        foreach($borrow as $media)
        {
          $title = $media->title;
        }


        $possessedborrows .= <<< EOT
        <ul class="flex_container">
            <li>${title}</li>
            <li>A rendre le ${date}</li>
        </ul>
EOT;
      }
      $returnedborrows='';
      foreach ($returned as $borrow) {
        $title="";
        $date = $borrow->borrow_date_end;
        $borrow = $borrow->media()->get();
        foreach($borrow as $media)
        {
          $title = $media->title;
        }


        $returnedborrows .= <<< EOT
        <ul class="flex_container">
            <li>${title}</li>
            <li>Retourné le ${date}</li>
        </ul>
EOT;
      }
      $html = "";
      $html .= <<<EOT
      <main id="recap_return">
          <h2>Adhérent n° ${iduser}, ${name} ${surname} </h2>
          <div class="flex_container">
              <div class="item__return">
                  <h3>Documents retournés</h3>
                  ${returnedborrows}
              </div>
              <div class="item__return">
                  <h3>Documents possédés</h3>
                    ${possessedborrows}
              </div>
          </div>
          <nav>
              <a href="${hrefHome}" id="nav_recap_borrow">ok</a>
          </nav>
      </main>
EOT;
        return $html;

    }


    protected function renderBody($selector)
    {
        $content = "";
        $navBar = "";
        switch ($selector) {
            case 'home':
                $content = $this->renderHome();
                break;
            case 'borrow':
                $navBar = $this->renderHeader();
                $content = $this->renderBorrow();
                break;
            case 'borrowUser':
                $navBar = $this->renderHeader();
                $content = $this->renderBorrowUser();
                break;
            case 'return' :
                $navBar = $this->renderHeader();
                $content = $this->renderReturn();
                break;

            case 'adddoc' :
                $navBar = $this->renderHeader();
                $content = $this->renderAddDoc();
                break;

            case 'userregister' :
                $navBar = $this->renderHeader();
                $content = $this->renderUserRegister();
                break;

            case 'userinfo' :
                $navBar = $this->renderHeader();
                $content = $this->renderUserInfo();
                break;

            case 'borrowsummary' :
                $navBar = $this->renderHeader();
                $content = $this->renderBorrowSummary();
                break;

            case 'returnsummary' :
                $navBar = $this->renderHeader();
                $content = $this->renderReturnSummary();
                break;
            default:
                $content = $this->renderHome();
                break;
        }


        $footer = $this->renderFooter();
        $html = <<<EOT
    <header> ${navBar} </header>

    <section>
      ${content}
    </section>
    <footer> ${footer} </footer>
EOT;

        return $html;
    }
}
