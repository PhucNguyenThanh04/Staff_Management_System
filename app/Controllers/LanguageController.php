<?php
require_once 'app/Controllers/Controller.php';

class LanguageController extends Controller {
    public function change() {
        $lang = isset($_GET['lang']) ? $_GET['lang'] : 'vi';
        LanguageHelper::setLanguage($lang);
        if (isset($_GET['redirect_url'])) {
            header('location: ' .$_GET['redirect_url']);
        } else {
            redirect('/');
        }
        exit;
    }
} 