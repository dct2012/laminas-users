<?php

declare( strict_types = 1 );

namespace App\Controller;

use Exception;
use App\Functions as F;
use App\Model\{Admin, Login, PageVisit, User};
use App\Model\Helper\{LoginHelper, PageVisitHelper};
use Laminas\Form\Form;
use Laminas\Db\Adapter\Adapter;
use Laminas\Validator\Identical;
use Laminas\Session\SessionManager;
use App\Exception\RefreshException;
use App\Exception\RedirectUserException;
use Laminas\Mvc\Plugin\Identity\Identity;
use App\Exception\RedirectAdminException;
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
	/** @var LoginHelper */
	protected LoginHelper $LoginHelper;
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
		$this->LoginHelper     = new LoginHelper( $this->db );
		$this->PageVisitHelper = new PageVisitHelper( $this->db );
	}

	/**
	 * @param string $left
	 * @param string $right
	 * @param string $errorMsg
	 *
	 * @return self
	 * @throws RefreshException
	 */
	protected function assertIdentical( string $left, string $right, string $errorMsg ): self {
		if( !( new Identical( $left ) )->isValid( $right ) ) {
			throw new RefreshException( $errorMsg );
		}

		return $this;
	}

	/**
	 * @param Exception $Exception
	 *
	 * @return self
	 * @throws Exception
	 */
	protected function assertLoggedIn( Exception $Exception ): self {
		if( !$this->getAuthenticationService()->hasIdentity() ) {
			throw $Exception;
		}

		return $this;
	}

	/**
	 * @param string $errorMsg
	 *
	 * @return self
	 * @throws RedirectAdminException
	 * @throws RedirectUserException
	 */
	protected function assertLoggedOut( string $errorMsg = 'You are already logged in!' ): self {
		if( $this->getAuthenticationService()->hasIdentity() ) {
			if( $this->isAdmin() ) {
				throw new RedirectAdminException( $errorMsg );
			}

			throw new RedirectUserException( $errorMsg );
		}

		return $this;
	}

	/**
	 * @param string $left
	 * @param string $right
	 * @param string $errorMsg
	 *
	 * @return self
	 * @throws RefreshException
	 */
	protected function assertNotIdentical( string $left, string $right, string $errorMsg ): self {
		if( ( new Identical( $left ) )->isValid( $right ) ) {
			throw new RefreshException( $errorMsg );
		}

		return $this;
	}

	/**
	 * @param string $password
	 *
	 * @return self
	 * @throws RefreshException
	 */
	protected function assertPasswordLength( string $password ): self {
		try {
			F::assertPasswordLength( $password );
		} catch( Exception $e ) {
			throw new RefreshException( $e->getMessage() );
		}

		return $this;
	}

	/**
	 * @param string $name
	 * @param string $password
	 *
	 * @return self
	 * @throws RefreshException
	 */
	protected function authenticateLogin( string $name, string $password ): self {
		$Result = $this->getAuthenticationService()->authenticate( $this->getAuthenticationAdapter()->setIdentity( $name )->setCredential( $password ) );
		if( !$Result->isValid() ) {
			throw new RefreshException( implode( '', $Result->getMessages() ) );
		}

		return $this;
	}

	/** @return Login */
	protected function createLogin(): Login {
		return $this->LoginHelper->create( new Login( $this->getIpAddress(), $this->getUserAgent() ) );
	}

	/**
	 * @param string $page
	 *
	 * @return PageVisit
	 */
	protected function createPageVisit( string $page ): PageVisit {
		return $this->PageVisitHelper->create( new PageVisit( $page, $this->getIpAddress(), $this->getUserAgent() ) );
	}

	/** @param string $msg */
	protected function flashMessengerError( string $msg ) {
		$this->getFlashMessenger()->addErrorMessage( $msg );
	}

	/** @param string $msg */
	protected function flashMessengerSuccess( string $msg ) {
		$this->getFlashMessenger()->addSuccessMessage( $msg );
	}

	/** @return AbstractAdapter */
	protected function getAuthenticationAdapter(): AdapterInterface {
		return $this->getAuthenticationService()->getAdapter();
	}

	/** @return AuthenticationService */
	protected function getAuthenticationService(): AuthenticationService {
		if( empty( $this->AuthenticationService ) ) {
			/** @var AuthenticationService $AuthenticationService */
			$AuthenticationService       = $this->getIdentity()->getAuthenticationService();
			$this->AuthenticationService = $AuthenticationService;
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

	/** @return Identity */
	protected function getIdentity(): Identity {
		if( empty( $this->Identity ) ) {
			$this->Identity = $this->plugin( 'identity' );
		}

		return $this->Identity;
	}

	/** @return string */
	protected function getIpAddress(): string {
		return $_SERVER[ 'REMOTE_ADDR' ];
	}

	/** @return string */
	protected function getUserAgent(): string {
		return $_SERVER[ 'HTTP_USER_AGENT' ];
	}

	/** @return bool */
	protected function isAdmin(): bool {
		return $this->getAuthenticationService()->getIdentity() instanceof Admin;
	}

	/** @return bool */
	protected function isUser(): bool {
		return $this->getAuthenticationService()->getIdentity() instanceof User;
	}

	/**
	 * @param Form $Form
	 *
	 * @return self
	 * @throws RefreshException
	 */
	protected function validateForm( Form $Form ): self {
		if( !$Form->setData( $this->getRequest()->getPost() )->isValid() ) {
			throw new RefreshException( implode( '', $Form->getMessages() ) );
		}

		return $this;
	}

	/**
	 * @param string $username
	 * @param string $password
	 *
	 * @return self
	 * @throws RefreshException
	 */
	protected function validatePassword( string $username, string $password ): self {
		$Result = $this->getAuthenticationAdapter()->setIdentity( $username )->setCredential( $password )->authenticate();
		if( !$Result->isValid() ) {
			throw new RefreshException( implode( '', $Result->getMessages() ) );
		}

		return $this;
	}
}