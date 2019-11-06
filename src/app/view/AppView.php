<?php

namespace app\view;

use mf\router\Router;

class AppView extends \mf\view\AbstractView
{

    private function renderHeader()
    {

    }


    private function renderFooter()
    {

    }


    protected function renderBody($selector)
    {
        $content = "";
        $navBar = "";
        switch ($selector) {
            case 'home':
                $navBar = $this->renderHeader();
                $content = $this->renderHome();
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
