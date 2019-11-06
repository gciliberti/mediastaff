<?php

namespace app\auth;

class AppAuthentification extends \mf\auth\Authentification {

    const ACCESS_LEVEL_USER  = 100;
    const ACCESS_LEVEL_ADMIN = 200;

    /* constructeur */
    public function __construct(){
        parent::__construct();
    }

}
