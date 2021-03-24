<?php
class Controller
{
    public static function init_routes()
    {
        $oauth = new OAuthController();
        $oauth::init();
        
        BaseController::init();
        PostController::init();
        AuthController::init();
        MenuController::init();
        WidgetController::init();
        ValidatorController::init();
    }
}
