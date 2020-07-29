<?php

namespace User\Controller;

use Exception;
use User\Model\User;
use User\Command\UserCommand;
use User\Form\{DeleteForm, InfoForm, LoginForm, LogoutForm, SignupForm, UpdateForm};
use UserLogin\Model\UserLogin;
use UserLogin\Command\UserLoginCommand;
use UserLogin\Enum\UserLoginFields as ULFs;
use Laminas\Form\Form;
use Laminas\View\Model\ViewModel;
use Laminas\Http\{Request, Response};
use Laminas\Validator\{Identical, StringLength};
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Adapter\AbstractAdapter;
use Laminas\Mvc\Plugin\Identity\Identity;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\Form\FormElementManager\FormElementManagerV3Polyfill as FormManager;

class UserController extends AbstractActionController {
	/** @var Form */
	protected Form $Form;
	/** @var Request */
	protected Request $Request;
	/** @var FlashMessenger */
	protected FlashMessenger $FM;
	/** @var Identity */
	protected Identity $Identity;
	/** @var FormManager */
	protected FormManager $FormManager;
	/** @var UserCommand */
	protected UserCommand $UserCommand;
	/** @var ?AbstractAdapter */
	protected ?AbstractAdapter $Adapter;
	/** @var AuthenticationService */
	protected AuthenticationService $AS;
	/** @var UserLoginCommand */
	protected UserLoginCommand $UserLoginCommand;

	/** @var string */
	protected string $ipAddress;
	/** @var string */
	protected string $device;

	/**
	 * @param UserCommand      $UserCommand
	 * @param UserLoginCommand $UserLoginCommand
	 * @param FormManager      $FormManager
	 */
	public function __construct( UserCommand $UserCommand, UserLoginCommand $UserLoginCommand, FormManager $FormManager ) {
		$this->UserCommand      = $UserCommand;
		$this->UserLoginCommand = $UserLoginCommand;
		$this->FormManager      = $FormManager;

		$this->ipAddress = $_SERVER[ 'REMOTE_ADDR' ];
		$this->device    = $_SERVER[ 'HTTP_USER_AGENT' ];
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
	 *
	 * @return ?Response
	 */
	protected function ensureLoggedIn( string $redirect, string $errorMsg ): ?Response {
		if( !$this->AS->hasIdentity() ) {
			$this->FM->addErrorMessage( $errorMsg );

			return $this->redirect()->toRoute( $redirect );
		}

		return null;
	}

	/** @return ?Response */
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
	 *
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
	 *
	 * @return ?Response
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
	 *
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
	 *
	 * @return ?Response
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
	 * @param User     $User
	 *
	 * @return ?Response
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

		$Response = $this->ensureLoggedIn( 'user/login', 'You have to be logged in to view user info!' );
		if( !empty( $Response ) ) {
			return $Response;
		}

		/* @var User $User */
		$User       = $this->UserCommand->read( $this->AS->getIdentity() );
		$UserLogins = $this->UserLoginCommand->read( new UserLogin( 0, '', '' ), [ ULFs::USER_ID => $User->getId() ] );
		if( !is_array( $UserLogins ) ) {
			$UserLogins = [ $UserLogins ];
		}

		return new ViewModel( [ 'Form' => $this->Form, 'User' => $User, 'UserLogins' => $UserLogins ] );
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
		$password = $data[ 'password' ];
		$Response = $this->ensureIdentical( $password, $data[ 'verify-password' ] );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$Response = $this->ensurePasswordConstraints( $password );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$Response = $this->validateForm();
		if( !empty( $Response ) ) {
			return $Response;
		}

		/* @var User $User */
		$User     = $this->Form->getData();
		$Response = $this->execUserFunc( [ $this->UserCommand, 'create' ], $User->setPassword( $password ) );
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

		$Response = $this->execUserFunc( [ $this->UserCommand, 'update' ], $User->setPassword( $newPassword ) );
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
		$User = $this->UserCommand->read( $User );

		$this->UserLoginCommand->create( new UserLogin( $User->getId(), $this->ipAddress, $this->device ) );
		$this->AS->getStorage()->write( $User );
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

		$Response = $this->execUserFunc( [ $this->UserCommand, 'delete' ], $User );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$this->AS->clearIdentity();
		$this->FM->addSuccessMessage( "Successfully deleted account: {$User->getUserName()}." );

		return $this->redirect()->toRoute( 'user/login' );
	}
}