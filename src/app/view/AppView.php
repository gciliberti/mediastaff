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

    public function renderHome(){
      $route = new \mf\router\Router();
      $hrefBorrow = $route->urlFor('borrow');
      $hrefAddDoc = $route->urlFor('addDoc');
      $hrefReturn = $route->urlFor('return');
      $hrefUsers = $route->urlFor('users');


      $html = "";
      $html .= <<< EOT
      <main id="home">
          <nav>
              <div class="container">
                  <a href="${hrefBorrow}">
                      <div class="item__nav">
                          <img src="#" alt="icone emprunt">
                          <h2>Emprunt</h2>
                      </div>
                  </a>
                  <a href="${hrefReturn}">
                      <div class="item">
                          <img src="#" alt="icone retour">
                          <h2>Retour</h2>
                      </div>
                  </a>
                  <a href="${hrefUsers}">
                      <div class="item">
                          <img src="#" alt="icone utilisateurs">
                          <h2>Utilisateurs</h2>
                      </div>
                  </a>
              </div>
              <a href="${hrefAddDoc}" class="adddoc"> <img src="#" alt="plus"> Ajouter un document</a>
          </nav>
      </main>
EOT;
    return $html;
    }

    public function renderReturn(){
      $router = new \mf\router\Router();
      $hrefCheckReturn = $router->urlFor('checkReturn');
      $html = "";
      $html .= <<<EOT
      <main id="return">
          <form action="${hrefCheckReturn}" name="return">
              <input type="text" name="ref" placeholder="Référence">
              <button type="submit">Valider</button>
          </form>
      </main>
EOT;
    return $html;
    }

    public function renderBorrow(){
      $router = new \mf\router\Router();
      $hrefCheckBorrow = $router->urlFor('checkBorrow');
      $html = "";
      $html .= <<<EOT
      <main id="borrow">
          <form action="${hrefCheckBorrow}" name="borrow">
              <div class="container borrow">
                  <div class="item borrow">
                      <input type="text" placeholder="Numéro d'adhérent">
                  </div>
                  <div class="item borrow">
                      <input type="text" placeholder="Référence du document">
                  </div>
              </div>
              <div class="item borrow">
                  <label for="dateRendu">Date de rendu</label>
                  <input type="date" id="dateRendu">
                  <button type="submit">Valider</button>
              </div>
          </form>
      </main>
EOT;
    return $html;
    }

    private function renderAddDoc(){
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

    private function renderUserRegister(){
      $html = "";
      $html .= <<<EOT
      <main id="users">
          <div class="container users">
              <div class="item__user__container">
                  <img src="" alt="photo de profil">
                  <p>adherent numéro 9</p>
                  <p>Prénom Nom</p>
                  <img src="" alt=check">
                  <img src="" alt=cross">
              </div>
          </div>

          <div class="flex_container">
              <nav>
                  <a href="">Créer un adhérent</a>
              </nav>
              <div class="item">
                  <h3>Informations sur un adhérent :</h3>
                  <form class="flex_container" action="" id="info_adherent">
                      <input type="number" placeholder="Numéro d'adhérent">
                      <button type="submit">ok</button>
                  </form>
              </div>
          </div>
      </main>
EOT;
    return $html;
    }

    private function renderUserInfo(){
      $html = "";
      $html .= <<<EOT
      <main id="profil_user">
          <div class="info">
              <ul>
                  <li>Corentin Roy</li>
                  <li>Pryxs</li>
                  <li>corentin@gmail.com</li>
              </ul>
          </div>
          <div class="where">
              <ul>
                  <li>4 chemin des Andalouses</li>
                  <li>54000 Nancy</li>
                  <li>0651513256</li>
              </ul>
          </div>
      </main>
EOT;
    return $html;
    }

    private function renderBorrowSummary(){
      $router = new \mf\router\Router();
      $hrefHome = $router->urlFor('home');
      $html = "";
      $html .= <<<EOT
      <main id="recap_borrow">
          <div>
              <h3>Adhérent numéro 14</h3>
              <ul class="flex_container">
                  <li>Titre 1</li>
                  <li>A retourner le 22/10</li>
              </ul>
              <ul class="flex_container">
                  <li>Titre 3</li>
                  <li>A retourner le 22/10</li>
              </ul>
              <ul class="flex_container">
                  <li>Titre 4</li>
                  <li>A retourner le 22/10</li>
              </ul>
          </div>
          <nav>
              <a href="${hrefHome}" id="nav_recap_borrow">ok</a>
          </nav>
      </main>
EOT;
    return $html;
    }

    private function renderReturnSummary(){
      $router = new \mf\router\Router();
      $hrefHome = $router->urlFor('home');
      $html = "";
      $html .= <<<EOT
      <main id="recap_return">
          <h2>Adhérent</h2>
          <div class="flex_container">
              <div class="item__return">
                  <h3>Documents retournés</h3>
                  <ul class="flex_container">
                      <li>Titre 1</li>
                      <li>Retourné le 22/10</li>
                  </ul>
                  <ul class="flex_container">
                      <li>Titre 3</li>
                      <li>Retourné le 22/10</li>
                  </ul>
                  <ul class="flex_container">
                      <li>Titre 4</li>
                      <li>Retourné le 22/10</li>
                  </ul>
              </div>
              <div class="item__return">
                  <h3>Documents possédés</h3>

                  <ul class="flex_container">
                      <li>Titre 1</li>
                      <li>A retourner le 22/10</li>
                  </ul>
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
