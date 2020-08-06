<?php

declare( strict_types = 1 );

namespace App\Controller;

use Laminas\Http\Response;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\{Sql, Update};
use Laminas\View\Model\ViewModel;
use Laminas\Session\SessionManager;
use Laminas\Form\FormElementManager\FormElementManagerV3Polyfill as FormManager;
use App\Functions;
use App\Enum\Routes;
use App\Exception\DbOperationHadNoAffectException;
use App\Model\{Identity, Admin, AdminLogin, User, UserLogin};
use App\Model\Values\{AbstractHasLoginFields as AHLFs, AbstractHasIdentityFields as AHIFs, IdentityFields as IFs, LoginFields as LFs};
use App\Form\User\{InfoForm as UserInfoForm, LoginForm as UserLoginForm, LogoutForm as UserLogoutForm};
use App\Form\Admin\{InfoForm as AdminInfoForm, LoginForm as AdminLoginForm, LogoutForm as AdminLogoutForm};
use App\Model\Helper\{AdminLoginHelper, AdminHelper, IdentityHelper, LoginHelper, UserHelper, UserLoginHelper};

abstract class AbstractHasIdentityController extends AbstractController {
	/** @var AdminHelper|UserHelper */
	protected $ModelHelper;
	/** @var LoginHelper */
	protected LoginHelper $LoginHelper;
	/** @var IdentityHelper */
	protected IdentityHelper $IdentityHelper;
	/** @var AdminLoginHelper|UserLoginHelper */
	protected $ModelLoginHelper;
	/** @var string */
	protected string $modelClassName, $modelLoginClassName, $infoFormClassName, $loginFormClassName, $logoutFormClassName, $infoRoute, $loginRoute, $logoutRoute;

	/**
	 * @param Adapter        $db
	 * @param FormManager    $FormManager
	 * @param SessionManager $SessionManager
	 */
	public function __construct( Adapter $db, FormManager $FormManager, SessionManager $SessionManager ) {
		parent::__construct( $db, $FormManager, $SessionManager );

		$this->LoginHelper    = new LoginHelper( $this->db );
		$this->IdentityHelper = new IdentityHelper( $this->db );
	}

	/**
	 * @param AdminHelper|UserHelper           $ModelHelper
	 * @param AdminLoginHelper|UserLoginHelper $ModelLoginHelper
	 */
	protected function setHelpers( $ModelHelper, $ModelLoginHelper ) {
		$this->ModelHelper      = $ModelHelper;
		$this->ModelLoginHelper = $ModelLoginHelper;

		$isAdmin = $this->isAdminController();

		$this->modelClassName      = $isAdmin ? Admin::class : User::class;
		$this->modelLoginClassName = $isAdmin ? AdminLogin::class : UserLogin::class;
		$this->infoRoute           = $isAdmin ? Routes::ADMIN : Routes::USER;
		$this->loginRoute          = $isAdmin ? Routes::ADMIN_LOGIN : Routes::USER_LOGIN;
		$this->logoutRoute         = $isAdmin ? Routes::ADMIN_LOGOUT : Routes::USER_LOGOUT;
		$this->infoFormClassName   = $isAdmin ? AdminInfoForm::class : UserInfoForm::class;
		$this->loginFormClassName  = $isAdmin ? AdminLoginForm::class : UserLoginForm::class;
		$this->logoutFormClassName = $isAdmin ? AdminLogoutForm::class : UserLogoutForm::class;
	}

	/**
	 * @param Identity $Identity
	 *
	 * @return Admin|User
	 */
	protected function createNewModel( Identity $Identity ) {
		$Model = $this->modelClassName;

		return new $Model( $Identity );
	}

	/**
	 * @param AdminLogin|UserLogin $ModelLogin
	 *
	 * @return Admin|User
	 */
	protected function getModelFromModelLogin( $ModelLogin ) {
		return $this->isAdminController() ? $ModelLogin->getAdmin() : $ModelLogin->getUser();
	}

	/**
	 * @param Admin|User $Model
	 * @param            $Login
	 *
	 * @return AdminLogin|UserLogin
	 */
	protected function createNewModelLogin( $Model, $Login ) {
		$M = $this->modelLoginClassName;

		return new $M( $Model, $Login );
	}

	/** @return bool */
	protected function isAdminController(): bool {
		return ( $this->ModelHelper instanceof AdminHelper );
	}

	/** @param Admin|User $Model */
	protected function updateNullLogouts( $Model ) {
		try {
			$type = $this->infoRoute;
			( new Sql( $this->db ) )->prepareStatementForSqlObject(
				( new Update( 'logins' ) )
					->set( [ LFs::LOGOUT_TIME => Functions::createTimeStamp() ] )
					->join( "{$type}_logins", "{$type}_logins.login_id = logins.id" )
					->join( "{$type}s", "{$type}s.id = {$type}_logins.{$type}_id" )
					->where( [ "{$type}s.id" => $Model->getId(), 'logins.logout_time' => null ] )
			)->execute();
		} catch( DbOperationHadNoAffectException $e ) {
			// It's ok if there's nothing to update
		}
	}

	/** @return Response|ViewModel */
	public function loginAction() {
		$Response = $this->ensureLoggedOut();
		if( !empty( $Response ) ) {
			return $Response;
		}
		$Form = $this->getForm( $this->loginFormClassName );
		if( !$this->getRequest()->isPost() ) {
			$this->PageVisitHelper->create( $this->createPageVisit( $this->loginRoute ) );

			return new ViewModel( [ 'Form' => $Form ] );
		}
		$Response = $this->validateForm( $Form );
		if( !empty( $Response ) ) {
			return $Response;
		}
		$data     = $Form->getData();
		$name     = $data[ IFs::NAME ];
		$password = $data[ IFs::PASSWORD ];
		$Response = $this->authenticateLogin( $name, $password );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$Identity = $this->IdentityHelper->read( new Identity( $name, $password ) );
		$Model    = $this->ModelHelper->read( $this->createNewModel( $Identity ), [ AHIFs::IDENTITY_ID => $Identity->getId() ] );
		$this->updateNullLogouts( $Model );

		$ModelLogin = $this->ModelLoginHelper->create( $this->createNewModelLogin( $Model, $this->LoginHelper->create( $this->createLogin() ) ) );
		$this->getAuthenticationService()->getStorage()->write( $this->getModelFromModelLogin( $ModelLogin )->setLogin( $ModelLogin->getLogin() ) );
		$this->getFlashMessenger()->addSuccessMessage( 'Successfully Logged In.' );

		return $this->redirect()->toRoute( $this->infoRoute );
	}

	/** @return Response|ViewModel */
	public function logoutAction() {
		$Response = $this->ensureLoggedIn( $this->loginRoute, 'You have to login before you can logout!' );
		if( !empty( $Response ) ) {
			return $Response;
		}
		if( !$this->getRequest()->isPost() ) {
			return $this->redirect()->refresh();
		}
		$Response = $this->validateForm( $this->getForm( $this->logoutFormClassName ) );
		if( !empty( $Response ) ) {
			return $Response;
		}

		/** @var Admin|User $Model */
		$Model = $this->getAuthenticationService()->getIdentity();
		$this->LoginHelper->update( $Model->getLogin() );
		$this->getAuthenticationService()->clearIdentity();
		$this->getFlashMessenger()->addSuccessMessage( 'Successfully Logged Out.' );

		return $this->redirect()->toRoute( $this->loginRoute );
	}

	/** @return Response|ViewModel */
	public function infoAction() {
		$Response = $this->ensureLoggedIn( $this->loginRoute, 'You have to be logged as a admin to view admin info!' );
		if( !empty( $Response ) ) {
			return $Response;
		}

		/* @var Admin|User $Model */
		$Model       = $this->getAuthenticationService()->getIdentity();
		$modelLogins = $this->ModelLoginHelper->read( $this->createNewModelLogin( $Model, $Model->getLogin() ), [ AHLFs::LOGIN_ID => $Model->getLogin()->getId() ] );
		$this->PageVisitHelper->create( $this->createPageVisit( $this->infoRoute ) );

		return new ViewModel( [
			'Form'                                                  => $this->getForm( $this->infoFormClassName ),
			$this->isAdminController() ? 'Admin' : 'User'           => $Model,
			$this->isAdminController() ? 'AdminLogin' : 'UserLogin' => ( is_array( $modelLogins ) && count( $modelLogins ) > 1 )
				? $modelLogins[ 1 ]
				: null
		] );
	}
}
