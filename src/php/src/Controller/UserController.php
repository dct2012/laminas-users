<?php

declare( strict_types = 1 );

namespace App\Controller;

use Exception;
use App\Enum\Routes;
use App\Model\{Identity, Login, User, UserLogin, UserPageVisit};
use App\Model\Helper\{IdentityHelper, UserLoginHelper, UserHelper, UserPageVisitHelper};
use App\Form\User\{DeleteForm, InfoForm, LoginForm, LogoutForm, SignupForm, UpdateForm};
use App\Model\Values\{IdentityFields as IFs, LoginFields as LFs, UserFields as UFs, UserLoginFields as ULFs};
use App\Exception\{RefreshException, RedirectUserException, RedirectAdminException, RedirectUserLoginException};
use Laminas\Http\Response;
use Laminas\Db\Adapter\Adapter;
use Laminas\View\Model\ViewModel;
use Laminas\Session\SessionManager;
use Laminas\Form\FormElementManager\FormElementManagerV3Polyfill as FormManager;

class UserController extends AbstractController {
	/** @var UserHelper */
	protected UserHelper $UserHelper;
	/** @var IdentityHelper */
	protected IdentityHelper $IdentityHelper;
	/** @var UserLoginHelper */
	protected UserLoginHelper $UserLoginHelper;
	/** @var UserPageVisitHelper */
	protected UserPageVisitHelper $UserPageVisitHelper;

	/**
	 * @param Adapter        $db
	 * @param FormManager    $FormManager
	 * @param SessionManager $SessionManager
	 */
	public function __construct( Adapter $db, FormManager $FormManager, SessionManager $SessionManager ) {
		parent::__construct( $db, $FormManager, $SessionManager );
		$this->UserHelper          = new UserHelper( $this->db );
		$this->IdentityHelper      = new IdentityHelper( $this->db );
		$this->UserLoginHelper     = new UserLoginHelper( $this->db );
		$this->UserPageVisitHelper = new UserPageVisitHelper( $this->db );
	}

	/**
	 * @param string $name
	 * @param string $password
	 *
	 * @return User
	 */
	protected function assertIdentityAndUser( string $name, string $password ): User {
		$Identity = $this->IdentityHelper->read( new Identity( $name, $password ) );
		if( empty( $Identity ) ) {
			throw new RefreshException( "Identity with name, '{$name}', does not exist!" );
		}
		$User = $this->UserHelper->read( new User( $Identity ), [ UFs::IDENTITY_ID => $Identity->getId() ] );
		if( empty( $User ) ) {
			throw new RefreshException( "User with name, '{$name}', does not exist!" );
		}

		return $User;
	}

	/**
	 * @return $this
	 * @throws RedirectAdminException
	 */
	protected function assertIsUser(): self {
		if( !$this->getAuthenticationService()->getIdentity() instanceof User ) {
			throw new RedirectAdminException( 'You must be a user to access that action!' );
		}

		return $this;
	}

	/**
	 * @param User $User
	 *
	 * @return UserLogin
	 */
	protected function createUserLogin( User $User ): UserLogin {
		return $this->UserLoginHelper->create( new UserLogin( $User, $this->createLogin() ) );
	}

	/**
	 * @param User   $User
	 * @param string $path
	 *
	 * @return UserPageVisit
	 */
	protected function createUserPageVisit( User $User, string $path ): UserPageVisit {
		$PageVisit     = $this->createPageVisit( $path );
		$UserPageVisit = new UserPageVisit( $User, $PageVisit );
		$UserPageVisit = $this->UserPageVisitHelper->create( $UserPageVisit );

		return $UserPageVisit;
	}

	/**
	 * @param User $User
	 *
	 * @return ?UserLogin
	 */
	protected function getLastUserLogin( User $User ): ?UserLogin {
		/** @var UserLogin $UserLogin */
		$Login     = $User->getLogin();
		$UserLogin = new UserLogin( $User, $Login );

		$userLoginData = $this->UserLoginHelper->read( $UserLogin, [ ULFs::USER_ID => $User->getId() ] );
		if( is_array( $userLoginData ) && count( $userLoginData ) > 1 ) {
			$UserLogin = $userLoginData[ 1 ];
			$Login     = $this->LoginHelper->read( $Login, [ LFs::ID => $UserLogin->getLoginId() ] );
			$UserLogin->setLogin( $Login );
		} else {
			$UserLogin = null;
		}

		return $UserLogin;
	}

	/** @return bool */
	protected function isUser(): bool {
		return $this->getAuthenticationService()->getIdentity() instanceof User;
	}

	/** @param User $User */
	protected function updateDanglingLogins( User $User ) {
		$UserLogin     = new UserLogin( $User, new Login( $this->getIpAddress(), $this->getUserAgent() ) );
		$userLoginData = $this->UserLoginHelper->read( $UserLogin, [ ULFs::USER_ID => $UserLogin->getUserId() ] );
		if( is_array( $userLoginData ) && !empty( $userLoginData ) ) {
			/** @var UserLogin $UserLogin */
			$UserLogin = $userLoginData[ 0 ];
			$loginIDs  = array_map( fn( UserLogin $UL ) => $UL->getLoginId(), $userLoginData );
			$this->LoginHelper->update( $UserLogin->getLogin(), [], [ LFs::ID => $loginIDs, LFs::LOGOUT_TIME => null ] );
		}
	}

	/**
	 * @param LoginForm $Form
	 *
	 * @throws Exception
	 */
	protected function handleLogin( LoginForm $Form ) {
		$this->validateForm( $Form );

		$data     = $Form->getData();
		$name     = $data[ IFs::NAME ];
		$password = $data[ IFs::PASSWORD ];

		// ensure Identity and User exist
		$User = $this->assertIdentityAndUser( $name, $password );

		// ensure credentials given are valid
		$this->authenticateLogin( $name, $password );

		// they're now logged in, update dangling Logins
		$this->updateDanglingLogins( $User );

		// they're now logged in, create the necessary logins
		$UserLogin = $this->createUserLogin( $User );
		$User->setLogin( $UserLogin->getLogin() );

		$this->getAuthenticationService()->getStorage()->write( $User );
		$this->flashMessengerSuccess( 'Successfully Logged In.' );
	}

	/** @return Response|ViewModel */
	public function loginAction() {
		try {
			$this->assertLoggedOut();

			/** @var LoginForm $Form */
			$Form = $this->getForm( LoginForm::class );

			if( !$this->getRequest()->isPost() ) {
				$this->createPageVisit( Routes::USER_LOGIN );

				return new ViewModel( [ 'Form' => $Form ] );
			}

			$this->handleLogin( $Form );
		} catch( Exception $e ) {
			$this->flashMessengerError( $e->getMessage() );

			if( $e instanceof RefreshException ) {
				return $this->redirect()->refresh();
			}
			if( $e instanceof RedirectAdminException ) {
				return $this->redirect()->toRoute( Routes::ADMIN );
			}
		}

		return $this->redirect()->toRoute( Routes::USER );
	}

	/** @return Response|ViewModel */
	public function logoutAction() {
		try {
			$this->assertLoggedIn( new  RedirectUserLoginException( 'You have to login before you can logout!' ) )
			     ->assertIsUser();

			if( !$this->getRequest()->isPost() ) {
				return $this->redirect()->toRoute( Routes::USER );
			}

			$this->validateForm( $this->getForm( LogoutForm::class ) );

			/** @var User */
			$User  = $this->getAuthenticationService()->getIdentity();
			$Login = $User->getLogin();
			$this->LoginHelper->update( $Login );

			$this->getAuthenticationService()->clearIdentity();
			$this->flashMessengerSuccess( 'Successfully Logged Out.' );
		} catch( Exception $e ) {
			$this->flashMessengerError( $e->getMessage() );

			if( $e instanceof RefreshException ) {
				return $this->redirect()->refresh();
			}
			if( $e instanceof RedirectAdminException ) {
				return $this->redirect()->toRoute( Routes::ADMIN );
			}
		}

		return $this->redirect()->toRoute( Routes::USER_LOGIN );
	}

	/** @return Response|ViewModel */
	public function infoAction() {
		try {
			$this->assertLoggedIn( new RedirectUserLoginException( 'You have to be logged in to view user info!' ) )
			     ->assertIsUser();

			/* @var User $User */
			$User = $this->getAuthenticationService()->getIdentity();
			$this->createUserPageVisit( $User, Routes::USER );

			return new ViewModel( [
				'Form'      => $this->getForm( InfoForm::class ),
				'User'      => $User,
				'UserLogin' => $this->getLastUserLogin( $User ),
			] );
		} catch( Exception $e ) {
			$this->getFlashMessenger()->addErrorMessage( $e->getMessage() );

			if( $e instanceof RedirectAdminException ) {
				return $this->redirect()->toRoute( Routes::ADMIN );
			}

			return $this->redirect()->toRoute( Routes::USER_LOGIN );
		}
	}

	/** @return Response|ViewModel */
	public function deleteAction() {
		try {
			$this->assertLoggedIn( new RedirectUserLoginException( 'You must be logged in to delete your account!' ) )
			     ->assertIsUser();

			/** @var User $User */
			$User = $this->getAuthenticationService()->getIdentity();
			$Form = $this->getForm( DeleteForm::class );

			if( !$this->getRequest()->isPost() ) {
				$this->PageVisitHelper->create( $this->createPageVisit( Routes::USER_DELETE ) );

				return new ViewModel( [ 'Form' => $Form, 'User' => $User ] );
			}

			$this->validateForm( $Form )
			     ->validatePassword( $User->getName(), $Form->getData()[ IFs::PASSWORD ] );

			$User = $this->UserHelper->delete( $User, [ IFs::ID => $User->getIdentityId() ] );

			$this->getAuthenticationService()->clearIdentity();
			$this->flashMessengerSuccess( "Successfully deleted account: {$User->getName()}." );
		} catch( Exception $e ) {
			$this->flashMessengerError( $e->getMessage() );

			if( $e instanceof RefreshException ) {
				return $this->redirect()->refresh();
			}
			if( $e instanceof RedirectAdminException ) {
				return $this->redirect()->toRoute( Routes::ADMIN );
			}
		}

		return $this->redirect()->toRoute( Routes::USER_LOGIN );
	}

	/** @return Response|ViewModel */
	public function signupAction() {
		try {
			$this->assertLoggedOut();

			$Form = $this->getForm( SignupForm::class );

			if( !$this->getRequest()->isPost() ) {
				$this->PageVisitHelper->create( $this->createPageVisit( Routes::USER_SIGNUP ) );

				return new ViewModel( [ 'Form' => $Form ] );
			}

			$this->validateForm( $Form );

			$data     = $Form->getData();
			$password = $data[ IFs::PASSWORD ];

			$this->assertIdentical( $password, $data[ SignupForm::VERIFY_PASSWORD ], 'Passwords must be the same!' )
			     ->assertPasswordLength( $password );

			$Identity = $this->IdentityHelper->create( new Identity( $data[ IFs::NAME ], $password ) );
			$User     = $this->UserHelper->create( new User( $Identity ) );

			$this->flashMessengerSuccess( "Successfully signed up user: {$User->getName()}." );
		} catch( Exception $e ) {
			$this->flashMessengerError( $e->getMessage() );

			if( $e instanceof RefreshException ) {
				return $this->redirect()->refresh();
			}
			if( $e instanceof RedirectAdminException ) {
				return $this->redirect()->toRoute( Routes::ADMIN );
			}
			if( $e instanceof RedirectUserException ) {
				return $this->redirect()->toRoute( Routes::USER );
			}
		}

		return $this->redirect()->toRoute( Routes::USER_LOGIN );
	}

	/** @return Response|ViewModel */
	public function updateAction() {
		try {
			$this->assertLoggedIn( new RedirectUserLoginException( 'You must be logged in to update password!' ) )
			     ->assertIsUser();

			/* @var User $User */
			$User = $this->getAuthenticationService()->getIdentity();
			$Form = $this->getForm( UpdateForm::class );

			if( !$this->getRequest()->isPost() ) {
				$this->createUserPageVisit( $User, Routes::USER_UPDATE );

				return new ViewModel( [ 'Form' => $Form, 'User' => $User ] );
			}

			$this->validateForm( $Form );

			$data              = $Form->getData();
			$currentPassword   = $data[ UpdateForm::CURRENT_PASSWORD ];
			$newPassword       = $data[ IFs::PASSWORD ];
			$verifyNewPassword = $data[ UpdateForm::VERIFY_NEW_PASSWORD ];

			$this->assertIdentical( $newPassword, $verifyNewPassword, 'New passwords are not the same!' )
			     ->assertNotIdentical( $currentPassword, $newPassword, 'Current password and new password must be different!' )
			     ->assertPasswordLength( $newPassword )
			     ->validatePassword( $User->getName(), $currentPassword );

			$User->setPassword( $newPassword )
			     ->setIdentity( $this->IdentityHelper->update( $User->getIdentity() ) );

			$this->getAuthenticationService()->clearIdentity();
			$this->flashMessengerSuccess( "Successfully updated password for user: {$User->getName()}." );
		} catch( Exception $e ) {
			$this->flashMessengerError( $e->getMessage() );

			if( $e instanceof RefreshException ) {
				return $this->redirect()->refresh();
			}
			if( $e instanceof RedirectAdminException ) {
				return $this->redirect()->toRoute( Routes::ADMIN );
			}
		}

		return $this->redirect()->toRoute( Routes::USER_LOGIN );
	}
}
