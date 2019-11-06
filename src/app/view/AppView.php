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
