<?php

namespace App\Controllers;


use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {
		session_start();
		$this->view->login = isset($_GET['login']) ? isset($_GET['login']) : '';
		if(!empty($_SESSION)) 
		{ header('Location: /timeline'); }
		$this->render('index', 'layout');
	}

	public function inscreverse() {

		$this->view->erroCadastro = false;

		$this->view->usuario = array(
			'nome' =>'',
			'email' => '',
			'senha' => '',
		);
		$this->render('inscreverse', 'layout');
	}
	public function registrar() {
		$usuario = Container::getModel('Usuario');

		$usuario->__set('nome', $_POST['nome']);
		$usuario->__set('email', $_POST['email']);
		$usuario->__set('senha', md5($_POST['senha']));
		

		if($usuario->validaCadastro() && empty($usuario->getUsuarioEmail())){
		
			
				$usuario->salvar();

				$this->render('Cadastro');
			} else {

				$this->view->usuario = array(
					'nome' => $_POST['nome'],
					'email' => $_POST['email'],
					'senha' => $_POST['senha'],
				);
					$this->view->erroCadastro = true;
					$this->render('inscreverse');
		}
			
		}

		public function AuthController() {

			$this->render('AuthController', 'layout');
		}
}


?>