<?php

declare( strict_types = 1 );

namespace App\Controller;

use Exception;
use App\Enum\Routes;
use App\Form\Admin\{InfoForm, LoginForm, LogoutForm};
use App\Model\{Admin, AdminLogin, AdminPageVisit, Identity, Login};
use App\Exception\{RefreshException, RedirectUserException, RedirectAdminLoginException};
use App\Model\Helper\{AdminLoginHelper, AdminHelper, AdminPageVisitHelper, IdentityHelper};
use App\Model\Values\{AdminFields as AFs, AdminLoginFields as ALFs, IdentityFields as IFs, LoginFields as LFs};
use Laminas\Http\Response;
use Laminas\Db\Adapter\Adapter;
use Laminas\View\Model\ViewModel;
use Laminas\Session\SessionManager;
use Laminas\Form\FormElementManager\FormElementManagerV3Polyfill as FormManager;

class AdminController extends AbstractController {
	/** @var AdminHelper */
	protected AdminHelper $AdminHelper;
	/** @var IdentityHelper */
	protected IdentityHelper $IdentityHelper;
	/** @var AdminLoginHelper */
	protected AdminLoginHelper $AdminLoginHelper;
	/** @var AdminPageVisitHelper */
	protected AdminPageVisitHelper $AdminPageVisitHelper;

	/**
	 * @param Adapter        $db
	 * @param FormManager    $FormManager
	 * @param SessionManager $SessionManager
	 */
	public function __construct( Adapter $db, FormManager $FormManager, SessionManager $SessionManager ) {
		parent::__construct( $db, $FormManager, $SessionManager );
		$this->AdminHelper          = new AdminHelper( $this->db );
		$this->IdentityHelper       = new IdentityHelper( $this->db );
		$this->AdminLoginHelper     = new AdminLoginHelper( $this->db );
		$this->AdminPageVisitHelper = new AdminPageVisitHelper( $this->db );
	}

	/**
	 * @return $this
	 * @throws RedirectUserException
	 */
	protected function assertIsAdmin(): self {
		if( !$this->isAdmin() ) {
			throw new RedirectUserException( 'You must be a admin to access that action!' );
		}

		return $this;
	}

	/**
	 * @param Admin $Admin
	 *
	 * @return AdminLogin
	 */
	protected function createAdminLogin( Admin $Admin ): AdminLogin {
		return $this->AdminLoginHelper->create( new AdminLogin( $Admin, $this->createLogin() ) );
	}

	/**
	 * @param Admin  $Admin
	 * @param string $path
	 *
	 * @return AdminPageVisit
	 */
	protected function createAdminPageVisit( Admin $Admin, string $path ): AdminPageVisit {
		$PageVisit      = $this->createPageVisit( $path );
		$AdminPageVisit = new AdminPageVisit( $Admin, $PageVisit );
		$AdminPageVisit = $this->AdminPageVisitHelper->create( $AdminPageVisit );

		return $AdminPageVisit;
	}

	/**
	 * @param Admin $Admin
	 *
	 * @return ?AdminLogin
	 */
	protected function getLastAdminLogin( Admin $Admin ): ?AdminLogin {
		/** @var AdminLogin $AdminLogin */
		$Login      = $Admin->getLogin();
		$AdminLogin = new AdminLogin( $Admin, $Login );

		$adminLoginData = $this->AdminLoginHelper->read( $AdminLogin, [ ALFs::ADMIN_ID => $Admin->getId() ] );
		if( is_array( $adminLoginData ) && count( $adminLoginData ) > 1 ) {
			$AdminLogin = $adminLoginData[ 1 ];
			$Login      = $this->LoginHelper->read( $Login, [ LFs::ID => $AdminLogin->getLoginId() ] );
			$AdminLogin->setLogin( $Login );
		} else {
			$AdminLogin = null;
		}

		return $AdminLogin;
	}

	/** @param Admin $Admin */
	protected function updateDanglingLogins( Admin $Admin ) {
		$AdminLogin     = new AdminLogin( $Admin, new Login( $this->getIpAddress(), $this->getUserAgent() ) );
		$adminLoginData = $this->AdminLoginHelper->read( $AdminLogin, [ ALFs::ADMIN_ID => $AdminLogin->getAdminId() ] );
		if( is_array( $adminLoginData ) && !empty( $adminLoginData ) ) {
			/** @var AdminLogin $AdminLogin */
			$AdminLogin = $adminLoginData[ 0 ];
			$loginIDs   = array_map( fn( AdminLogin $AL ) => $AL->getLoginId(), $adminLoginData );
			$this->LoginHelper->update( $AdminLogin->getLogin(), [], [ LFs::ID => $loginIDs, LFs::LOGOUT_TIME => null ] );
		}
	}

	/**
	 * @param LoginForm $Form
	 *
	 * @throws RefreshException
	 */
	protected function handleLogin( LoginForm $Form ) {
		$this->validateForm( $Form );

		$data     = $Form->getData();
		$name     = $data[ IFs::NAME ];
		$password = $data[ IFs::PASSWORD ];

		// ensure Identity and Admin exist
		$Identity = $this->IdentityHelper->read( new Identity( $name, $password ) );
		$Admin    = $this->AdminHelper->read( new Admin( $Identity ), [ AFs::IDENTITY_ID => $Identity->getId() ] );

		// ensure credentials given are valid
		$this->authenticateLogin( $name, $password );

		// they're now logged in, update dangling Logins
		$this->updateDanglingLogins( $Admin );

		// they're now logged in, create the necessary logins
		$AdminLogin = $this->createAdminLogin( $Admin );
		$Admin->setLogin( $AdminLogin->getLogin() );

		$this->getAuthenticationService()->getStorage()->write( $Admin );
		$this->flashMessengerSuccess( 'Successfully Logged In.' );
	}

	/** @return Response|ViewModel */
	public function loginAction() {
		try {
			$this->assertLoggedOut();

			/** @var LoginForm $Form */
			$Form = $this->getForm( LoginForm::class );

			if( !$this->getRequest()->isPost() ) {
				$this->createPageVisit( Routes::ADMIN_LOGIN );

				return new ViewModel( [ 'Form' => $Form ] );
			}

			$this->handleLogin( $Form );
		} catch( Exception $e ) {
			$this->flashMessengerError( $e->getMessage() );

			if( $e instanceof RefreshException ) {
				return $this->redirect()->refresh();
			}
			if( $e instanceof RedirectUserException ) {
				return $this->redirect()->toRoute( Routes::USER );
			}
		}

		return $this->redirect()->toRoute( Routes::ADMIN );
	}

	/** @return Response|ViewModel */
	public function logoutAction() {
		try {
			$this->assertLoggedIn( new RedirectAdminLoginException( 'You have to login before you can logout!' ) )
			     ->assertIsAdmin();

			if( !$this->getRequest()->isPost() ) {
				return $this->redirect()->toRoute( Routes::ADMIN );
			}

			$this->validateForm( $this->getForm( LogoutForm::class ) );

			/** @var $Admin */
			$Admin = $this->getAuthenticationService()->getIdentity();
			$Login = $Admin->getLogin();
			$this->LoginHelper->update( $Login );

			$this->getAuthenticationService()->clearIdentity();
			$this->flashMessengerSuccess( 'Successfully Logged Out.' );
		} catch( Exception $e ) {
			$this->flashMessengerError( $e->getMessage() );

			if( $e instanceof RefreshException ) {
				return $this->redirect()->refresh();
			}
			if( $e instanceof RedirectUserException ) {
				return $this->redirect()->toRoute( Routes::USER );
			}
		}

		return $this->redirect()->toRoute( Routes::ADMIN_LOGIN );
	}

	/** @return Response|ViewModel */
	public function infoAction() {
		try {
			$this->assertLoggedIn( new RedirectAdminLoginException( 'You have to be logged in to view admin info!' ) )
			     ->assertIsAdmin();

			/* @var Admin $Admin */
			$Admin = $this->getAuthenticationService()->getIdentity();
			$this->createAdminPageVisit( $Admin, Routes::ADMIN );

			return new ViewModel( [
				'Form'       => $this->getForm( InfoForm::class ),
				'Admin'      => $Admin,
				'AdminLogin' => $this->getLastAdminLogin( $Admin )
			] );
		} catch( Exception $e ) {
			$this->flashMessengerError( $e->getMessage() );

			if( $e instanceof RedirectAdminLoginException ) {
				return $this->redirect()->toRoute( Routes::ADMIN_LOGIN );
			}
			if( $e instanceof RedirectUserException ) {
				return $this->redirect()->toRoute( Routes::USER );
			}
		}

		return $this->redirect()->refresh();

	}
}
