<?php

namespace App\Controllers;


use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

    public function timeline() {

        $this->validaAuth();


            $tweet = Container::getModel('Tweet');

            $tweet->__set('id_usuario', $_SESSION['id']);

            $tweets = $tweet->getAll();
            
            $this->view->tweets = $tweets;

            $usuario = Container::getModel('Usuario');
            $usuario->__set('id', $_SESSION['id']);

            $this->view->all_tweets = $usuario->getAllTweets();
            $this->view->all_seguindo = $usuario->getAllSeguindo();
            $this->view->all_seguidores = $usuario->getAllSeguidores();



            $this->render('timeline');
        }
    

    public function sair() {
    session_start();
    session_destroy();
    header('Location: /');

    }

    public function tweet() {
        session_start();

        $this->validaAuth();


            $tweet = Container::getModel('Tweet');



            $tweet->__set('tweet', $_POST['tweet']);
            $tweet->__set('id_usuario', $_SESSION['id']);
            $tweet->__get('id_usuario');

            $tweet->salvarTweet();

            header('Location: /timeline');
       
    }

    public function validaAuth()
    {
        session_start();
        if(!isset($_SESSION['id'])  || $_SESSION['id'] == ''  || !isset($_SESSION['nome']) ||  $_SESSION['nome'] == '') {
            header('Location: /?login=erro');
        }
    }

    public function quem_seguir() {

        $this->validaAuth();

        $pesquisarPor = isset($_GET['pesquisarPor']) ?  $_GET['pesquisarPor'] : '';
        echo '<br><br><br><br><br><br>';
        echo 'Pesquisar por: '.$pesquisarPor;
        
        
        
        $usuarios = array();

        if($pesquisarPor != '') {
            $usuario = Container::getModel('Usuario');
            $usuario->__set('nome', $pesquisarPor);
            $usuario->__set('id', $_SESSION['id']);
            $usuarios = $usuario->getAll();

        }

        $this->view->usuarios = $usuarios;

        $this->render('quem_seguir');
    }

    public function acao()
    {
        
        $this->validaAuth();
        print_r($usuario = Container::getModel('Usuario'));
        $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
        $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';


        $usuario->__set('id', $_SESSION['id']);

        if($acao == 'seguir') {
            $usuario->seguirUsuario($id_usuario_seguindo);
            header('Location: /quem_seguir');

        } else if ($acao == 'deixar_de_seguir') {
            $usuario->deixarSeguirUsuario($id_usuario_seguindo);
            header('Location: /quem_seguir');
        }



    }

    
}