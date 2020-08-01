<?php

declare( strict_types = 1 );

namespace App\Controller;

use Exception;
use App\Model\User;
use Laminas\Form\Form;
use Laminas\Http\Response;
use Laminas\Validator\Identical;
use Laminas\Validator\StringLength;
use Laminas\Session\SessionManager;
use Laminas\Mvc\Plugin\Identity\Identity;
use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\Authentication\Adapter\{AbstractAdapter, AdapterInterface};
use Laminas\Form\FormElementManager\FormElementManagerV3Polyfill as FormManager;

class AbstractController extends AbstractActionController {
	/** @var Identity */
	protected Identity $Identity;
	/** @var FormManager */
	protected FormManager $FormManager;
	/** @var SessionManager */
	protected SessionManager $SessionManager;
	/** @var FlashMessenger */
	protected FlashMessenger $FlashMessenger;
	/** @var AuthenticationService */
	protected AuthenticationService $AuthenticationService;

	/**
	 * @param SessionManager $SessionManager
	 * @param FormManager    $FormManager
	 */
	public function __construct( SessionManager $SessionManager, FormManager $FormManager ) {
		$this->SessionManager = $SessionManager;
		$this->FormManager    = $FormManager;
	}

	/**
	 * @param User $User
	 *
	 * @return ?Response
	 */
	protected function authenticateLogin( User $User ): ?Response {
		$Result = $this->getAuthenticationService()->authenticate(
			$this->getAuthenticationAdapter()
			     ->setIdentity( $User->getUserName() )
			     ->setCredential( $User->getPassword() )
		);
		if( $Result->isValid() ) {
			return null;
		}

		foreach( $Result->getMessages() as $error ) {
			$this->getFlashMessenger()->addErrorMessage( $error );
		}

		return $this->redirect()->refresh();
	}

	/** @return AbstractAdapter */
	protected function getAuthenticationAdapter(): AdapterInterface {
		return $this->getAuthenticationService()->getAdapter();
	}

	/** @return Identity */
	protected function getIdentity(): Identity {
		if( empty( $this->Identity ) ) {
			$this->Identity = $this->plugin( 'identity' );
		}

		return $this->Identity;
	}

	/** @return AuthenticationService */
	protected function getAuthenticationService(): AuthenticationService {
		if( empty( $this->AuthenticationService ) ) {
			$this->AuthenticationService = $this->getIdentity()->getAuthenticationService();
		}

		return $this->AuthenticationService;
	}

	/** @return FlashMessenger */
	protected function getFlashMessenger(): FlashMessenger {
		if( empty( $this->FlashMessenger ) ) {
			$this->FlashMessenger = $this->plugin( 'flashMessenger' );
		}

		return $this->FlashMessenger;
	}

	/**
	 * @param string $formClass
	 *
	 * @return Form
	 */
	protected function getForm( string $formClass ): object {
		return $this->FormManager->get( $formClass );
	}

	/**
	 * @param string $left
	 * @param string $right
	 * @param string $errorMsg
	 *
	 * @return ?Response
	 */
	protected function ensureIdentical( string $left, string $right, string $errorMsg = 'Passwords are not identical!' ): ?Response {
		if( ( new Identical( $left ) )->isValid( $right ) ) {
			return null;
		}

		$this->getFlashMessenger()->addErrorMessage( $errorMsg );

		return $this->redirect()->refresh();
	}

	/**
	 * @param string $redirect
	 * @param string $errorMsg
	 *
	 * @return ?Response
	 */
	protected function ensureLoggedIn( string $redirect, string $errorMsg ): ?Response {
		if( $this->getAuthenticationService()->hasIdentity() ) {
			return null;
		}

		$this->getFlashMessenger()->addErrorMessage( $errorMsg );

		return $this->redirect()->toRoute( $redirect );
	}

	/** @return ?Response */
	protected function ensureLoggedOut(): ?Response {
		if( !$this->getAuthenticationService()->hasIdentity() ) {
			return null;
		}

		$this->getFlashMessenger()->addInfoMessage( 'You are already logged in.' );

		return $this->redirect()->toRoute( 'user' );
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
		if( $StringLength->isValid( $password ) ) {
			return null;
		}

		foreach( $StringLength->getMessages() as $error ) {
			$this->getFlashMessenger()->addErrorMessage( $error );
		}

		return $this->redirect()->refresh();
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
			$this->getFlashMessenger()->addErrorMessage( $e->getMessage() );

			return $this->redirect()->refresh();
		}

		return null;
	}

	/**
	 * @param Form $Form
	 *
	 * @return ?Response
	 */
	protected function validateForm( Form $Form ): ?Response {
		$Form->setData( $this->getRequest()->getPost() );

		if( $Form->isValid() ) {
			return null;
		}

		foreach( $Form->getMessages() as $error ) {
			$this->getFlashMessenger()->addErrorMessage( $error );
		}

		return $this->redirect()->refresh();
	}

	/**
	 * @param string $username
	 * @param string $password
	 *
	 * @return ?Response
	 */
	protected function validatePassword( string $username, string $password ): ?Response {
		$Result = $this->getAuthenticationAdapter()->setIdentity( $username )->setCredential( $password )->authenticate();
		if( $Result->isValid() ) {
			return null;
		}

		foreach( $Result->getMessages() as $error ) {
			$this->getFlashMessenger()->addErrorMessage( $error );
		}

		return $this->redirect()->refresh();
	}
}