<?php

namespace User\Controller;

use Exception;
use User\Model\User;
use User\Command\UserCommand;
use User\Form\{DeleteForm, InfoForm, LoginForm, LogoutForm, SignupForm, UpdateForm};
use Laminas\Form\Form;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\View\Model\ViewModel;
use Laminas\Validator\{Identical, StringLength};
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Adapter\AbstractAdapter;
use Laminas\Mvc\Plugin\Identity\Identity;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\Form\FormElementManager\FormElementManagerV3Polyfill as FormManager;

class UserController extends AbstractActionController {
	/** @var UserCommand */
	protected UserCommand $Command;
	/** @var FormManager */
	protected FormManager $FormManager;
	protected AuthenticationService $AS;
	protected FlashMessenger $FM;
	protected Form $Form;
	protected Identity $Identity;
	protected Request $Request;
	protected ?AbstractAdapter $Adapter;

	/**
	 * @param UserCommand $Command
	 * @param FormManager $FormManager
	 */
	public function __construct( UserCommand $Command, FormManager $FormManager ) {
		$this->Command     = $Command;
		$this->FormManager = $FormManager;
	}

	/** @param string $formClass */
	protected function init( string $formClass ) {
		/* @var Form $Form */
		$Form           = $this->FormManager->get( $formClass );
		$this->Form     = $Form;
		$this->Identity = $this->plugin( 'identity' );
		$this->AS       = $this->Identity->getAuthenticationService();
		$this->FM       = $this->plugin( 'flashMessenger' );
		$this->Request  = $this->getRequest();
		$this->Adapter  = $this->AS->getAdapter();
	}

	/**
	 * @param string $redirect
	 * @param string $errorMsg
	 * @return ?Response
	 */
	protected function ensureLoggedIn( string $redirect, string $errorMsg ): ?Response {
		if( !$this->AS->hasIdentity() ) {
			$this->FM->addErrorMessage( $errorMsg );
			return $this->redirect()->toRoute( $redirect );
		}

		return null;
	}

	/** @return Response|null */
	protected function ensureLoggedOut(): ?Response {
		if( $this->AS->hasIdentity() ) {
			$this->FM->addInfoMessage( 'You are already logged in.' );
			return $this->redirect()->toRoute( 'user' );
		}

		return null;
	}

	/** @return ?Response */
	protected function validateForm(): ?Response {
		$this->Form->setData( $this->Request->getPost() );
		if( !$this->Form->isValid() ) {
			foreach( $this->Form->getMessages() as $error ) {
				$this->FM->addErrorMessage( $error );
			}
			return $this->redirect()->refresh();
		}

		return null;
	}

	/**
	 * @param string $left
	 * @param string $right
	 * @param string $errorMsg
	 * @return ?Response
	 */
	protected function ensureIdentical( string $left, string $right, string $errorMsg = 'Passwords are not identical!' ): ?Response {
		if( !( new Identical( $left ) )->isValid( $right ) ) {
			$this->FM->addErrorMessage( $errorMsg );
			return $this->redirect()->refresh();
		}

		return null;
	}

	/**
	 * @param string $password
	 * @return Response|null
	 */
	protected function ensurePasswordConstraints( string $password ): ?Response {
		$StringLength = ( new StringLength( [ 'min' => 8, 'max' => 100 ] ) )
			->setMessage( 'The password is less than %min% characters long', StringLength::TOO_SHORT )
			->setMessage( 'The password is more than %max% characters long', StringLength::TOO_LONG );
		if( !$StringLength->isValid( $password ) ) {
			foreach( $StringLength->getMessages() as $error ) {
				$this->FM->addErrorMessage( $error );
			}
			return $this->redirect()->refresh();
		}

		return null;
	}

	/**
	 * @param string $username
	 * @param string $password
	 * @return ?Response
	 */
	protected function validatePassword( string $username, string $password ): ?Response {
		$Result = $this->Adapter->setIdentity( $username )->setCredential( $password )->authenticate();
		if( !$Result->isValid() ) {
			foreach( $Result->getMessages() as $error ) {
				$this->FM->addErrorMessage( $error );
			}
			return $this->redirect()->refresh();
		}

		return null;
	}

	/**
	 * @param User $User
	 * @return Response|null
	 */
	protected function authenticateLogin( User $User ): ?Response {
		$this->Adapter->setIdentity( $User->getUserName() )->setCredential( $User->getPassword() );
		$Result = $this->AS->authenticate( $this->Adapter );
		if( !$Result->isValid() ) {
			foreach( $Result->getMessages() as $error ) {
				$this->FM->addErrorMessage( $error );
			}
			return $this->redirect()->refresh();
		}

		return null;
	}

	/**
	 * @param callable $func
	 * @param User $User
	 * @return Response|null
	 */
	protected function execUserFunc( callable $func, User $User ): ?Response {
		try {
			$func( $User );
		} catch( Exception $e ) {
			$this->FM->addErrorMessage( $e->getMessage() );
			return $this->redirect()->refresh();
		}

		return null;
	}

	/** @return Response|ViewModel */
	public function infoAction() {
		$this->init( InfoForm::class );

		return $this->ensureLoggedIn( 'user/login', 'You have to be logged in to view user info!' )
			?? new ViewModel( [ 'Form' => $this->Form, 'User' => $this->AS->getIdentity() ] );
	}

	/** @return Response|ViewModel */
	public function signupAction() {
		$this->init( SignupForm::class );

		$Response = $this->ensureLoggedOut();
		if( !empty( $Response ) ) {
			return $Response;
		}

		if( !$this->Request->isPost() ) {
			return new ViewModel( [ 'Form' => $this->Form ] );
		}

		$data     = $this->Request->getPost();
		$Response = $this->ensureIdentical( $data[ 'password' ], $data[ 'verify-password' ] );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$Response = $this->ensurePasswordConstraints( $data[ 'password' ] );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$Response = $this->validateForm();
		if( !empty( $Response ) ) {
			return $Response;
		}

		/* @var User $User */
		$User     = $this->Form->getData();
		$Response = $this->execUserFunc( [ $this->Command, 'create' ], $User );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$this->FM->addSuccessMessage( "Successfully signed up user: {$User->getUserName()}." );

		return $this->redirect()->toRoute( 'user/login' );
	}

	/** @return Response|ViewModel */
	public function updateAction() {
		$this->init( UpdateForm::class );

		$Response = $this->ensureLoggedIn( 'user/login', 'You must be logged in to update password!' );
		if( !empty( $Response ) ) {
			return $Response;
		}

		/* @var User $User */
		$User = $this->AS->getIdentity();
		if( !$this->Request->isPost() ) {
			return new ViewModel( [ 'Form' => $this->Form, 'User' => $User ] );
		}

		$data              = $this->Request->getPost();
		$currentPassword   = $data[ 'current_password' ];
		$newPassword       = $data[ 'password' ];
		$verifyNewPassword = $data[ 'verify_new_password' ];

		$Response = $this->ensureIdentical( $newPassword, $verifyNewPassword, 'New passwords are not identical!' );
		if( !empty( $Response ) ) {
			return $Response;
		}
		if( ( new Identical( $newPassword ) )->isValid( $currentPassword ) ) {
			$this->FM->addErrorMessage( 'Current password and new password must be different!' );
			return $this->redirect()->refresh();
		}

		$Response = $this->ensurePasswordConstraints( $newPassword );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$Response = $this->validateForm();
		if( !empty( $Response ) ) {
			return $Response;
		}

		$User     = $this->Form->getData();
		$Response = $this->validatePassword( $User->getUserName(), $currentPassword );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$Response = $this->execUserFunc( [ $this->Command, 'update' ], $User );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$this->AS->clearIdentity();
		$this->FM->addSuccessMessage( "Successfully updated password for user: {$User->getUserName()}." );

		return $this->redirect()->toRoute( 'user/login' );
	}

	/** @return Response|ViewModel */
	public function logoutAction() {
		$this->init( LogoutForm::class );

		$Response = $this->ensureLoggedIn( 'user/login', 'You have to login before you can logout!' );
		if( !empty( $Response ) ) {
			return $Response;
		}

		if( !$this->Request->isPost() ) {
			return $this->redirect()->refresh();
		}

		$Response = $this->validateForm();
		if( !empty( $Response ) ) {
			return $Response;
		}

		$this->AS->clearIdentity();
		$this->FM->addSuccessMessage( 'Successfully Logged Out.' );

		return $this->redirect()->toRoute( 'user/login' );
	}

	/** @return Response|ViewModel */
	public function loginAction() {
		$this->init( LoginForm::class );

		$Response = $this->ensureLoggedOut();
		if( !empty( $Response ) ) {
			return $Response;
		}

		if( !$this->Request->isPost() ) {
			return new ViewModel( [ 'Form' => $this->Form ] );
		}

		$Response = $this->validateForm();
		if( !empty( $Response ) ) {
			return $Response;
		}

		/* @var User $User */
		$User     = $this->Form->getData();
		$Response = $this->authenticateLogin( $User );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$userData = $this->Adapter->getResultRowObject( [ 'id', 'password' ] );
		$this->AS->getStorage()->write( $User->setID( $userData->id )->setPassword( $userData->password ) );

		$this->FM->addSuccessMessage( 'Successfully Logged In.' );

		return $this->redirect()->toRoute( 'user' );
	}

	/** @return Response|ViewModel */
	public function deleteAction() {
		$this->init( DeleteForm::class );

		$Response = $this->ensureLoggedIn( 'user/login', 'You must be logged in to delete your account!' );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$User = $this->AS->getIdentity();
		if( !$this->Request->isPost() ) {
			return new ViewModel( [ 'Form' => $this->Form, 'User' => $User ] );
		}

		$Response = $this->validateForm();
		if( !empty( $Response ) ) {
			return $Response;
		}

		/* @var User $User */
		$User     = $this->Form->getData();
		$Response = $this->validatePassword( $User->getUserName(), $User->getPassword() );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$Response = $this->execUserFunc( [ $this->Command, 'delete' ], $User );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$this->AS->clearIdentity();
		$this->FM->addSuccessMessage( "Successfully deleted account: {$User->getUserName()}." );

		return $this->redirect()->toRoute( 'user/login' );
	}
}