<?php

declare( strict_types = 1 );

namespace App\Controller;

use Exception;
use App\Model\Helper\PageVisitHelper;
use App\Model\{Login, PageVisit, User};
use Laminas\Form\Form;
use Laminas\Http\Response;
use Laminas\Db\Adapter\Adapter;
use Laminas\Session\SessionManager;
use Laminas\Mvc\Plugin\Identity\Identity;
use Laminas\Validator\{Identical, StringLength};
use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\Authentication\Adapter\{AbstractAdapter, AdapterInterface};
use Laminas\Form\FormElementManager\FormElementManagerV3Polyfill as FormManager;

class AbstractController extends AbstractActionController {
	/** @var Adapter */
	protected Adapter $db;
	/** @var Identity */
	protected Identity $Identity;
	/** @var FormManager */
	protected FormManager $FormManager;
	/** @var SessionManager */
	protected SessionManager $SessionManager;
	/** @var FlashMessenger */
	protected FlashMessenger $FlashMessenger;
	/** @var PageVisitHelper */
	protected PageVisitHelper $PageVisitHelper;
	/** @var AuthenticationService */
	protected AuthenticationService $AuthenticationService;

	/**
	 * @param Adapter        $db
	 * @param FormManager    $FormManager
	 * @param SessionManager $SessionManager
	 */
	public function __construct( Adapter $db, FormManager $FormManager, SessionManager $SessionManager ) {
		$this->db              = $db;
		$this->FormManager     = $FormManager;
		$this->SessionManager  = $SessionManager;
		$this->PageVisitHelper = new PageVisitHelper( $this->db );
	}

	/**
	 * @param string $name
	 * @param string $password
	 *
	 * @return ?Response
	 */
	protected function authenticateLogin( string $name, string $password ): ?Response {
		$Result = $this->getAuthenticationService()->authenticate( $this->getAuthenticationAdapter()->setIdentity( $name )->setCredential( $password ) );
		if( $Result->isValid() ) {
			return null;
		}

		foreach( $Result->getMessages() as $error ) {
			$this->getFlashMessenger()->addErrorMessage( $error );
		}

		return $this->redirect()->refresh();
	}

	/** @return Login */
	protected function createLogin(): Login {
		return new Login( $this->getIpAddress(), $this->getUserAgent() );
	}

	/**
	 * @param string $page
	 *
	 * @return PageVisit
	 */
	protected function createPageVisit( string $page ): PageVisit {
		return new PageVisit( $page, $this->getIpAddress(), $this->getUserAgent() );
	}

	/** @return AbstractAdapter */
	protected function getAuthenticationAdapter(): AdapterInterface {
		return $this->getAuthenticationService()->getAdapter();
	}

	/** @return AuthenticationService */
	protected function getAuthenticationService(): AuthenticationService {
		if( empty( $this->AuthenticationService ) ) {
			$this->AuthenticationService = $this->getIdentity()->getAuthenticationService();
		}

		return $this->AuthenticationService;
	}

	/** @return Identity */
	protected function getIdentity(): Identity {
		if( empty( $this->Identity ) ) {
			$this->Identity = $this->plugin( 'identity' );
		}

		return $this->Identity;
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

	/** @return string */
	protected function getIpAddress(): string {
		return $_SERVER[ 'REMOTE_ADDR' ];
	}

	/** @return string */
	protected function getUserAgent(): string {
		return $_SERVER[ 'HTTP_USER_AGENT' ];
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