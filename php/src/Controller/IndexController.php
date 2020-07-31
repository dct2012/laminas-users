<?php

declare( strict_types = 1 );

namespace App\Controller;

use App\Model\{PageVisit, User, UserLogin};
use App\Model\Values\UserLoginFields as ULFs;
use App\Exception\DbOperationHadNoAffectException;
use App\Model\Helper\{PageVisitModelHelper, UserLoginModelHelper, UserModelHelper};
use App\Form\{DeleteForm, InfoForm, LoginForm, LogoutForm, SignupForm, UpdateForm};
use Laminas\Http\Response;
use Laminas\Validator\Identical;
use Laminas\View\Model\ViewModel;
use Laminas\Form\FormElementManager\FormElementManagerV3Polyfill as FormManager;

class IndexController extends AbstractController {
	/** @var UserModelHelper */
	protected UserModelHelper $UserModelHelper;
	/** @var PageVisitModelHelper */
	protected PageVisitModelHelper $PageVisitModelHelper;
	/** @var UserLoginModelHelper */
	protected UserLoginModelHelper $UserLoginModelHelper;

	public function __construct( FormManager $FormManager, UserModelHelper $UserModelHelper, UserLoginModelHelper $UserLoginModelHelper, PageVisitModelHelper $PageVisitModelHelper ) {
		parent::__construct( $FormManager );

		$this->UserModelHelper      = $UserModelHelper;
		$this->UserLoginModelHelper = $UserLoginModelHelper;
		$this->PageVisitModelHelper = $PageVisitModelHelper;
	}

	/** @return Response|ViewModel */
	public function deleteAction() {
		$Response = $this->ensureLoggedIn( 'user/login', 'You must be logged in to delete your account!' );
		if( !empty( $Response ) ) {
			return $Response;
		}

		/** @var User $User */
		$User = $this->getAuthenticationService()->getIdentity();
		$Form = $this->getForm( DeleteForm::class );
		if( !$this->getRequest()->isPost() ) {
			$this->PageVisitModelHelper->create( new PageVisit( 'user/delete', $_SERVER[ 'REMOTE_ADDR' ], $_SERVER[ 'HTTP_USER_AGENT' ], $User->getId() ) );

			return new ViewModel( [ 'Form' => $Form, 'User' => $User, 'PageVisit' ] );
		}

		$Response = $this->validateForm( $Form );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$User     = $Form->getData();
		$Response = $this->validatePassword( $User->getUserName(), $User->getPassword() );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$Response = $this->execUserFunc( [ $this->UserModelHelper, 'delete' ], $User );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$this->getAuthenticationService()->clearIdentity();
		$this->getFlashMessenger()->addSuccessMessage( "Successfully deleted account: {$User->getUserName()}." );

		return $this->redirect()->toRoute( 'user/login' );
	}

	/** @return Response */
	public function indexAction(): Response {
		return $this->redirect()->toRoute(
			( $this->plugin( 'identity' )->getAuthenticationService() )->hasIdentity()
				? 'user'
				: 'user/login' );
	}

	/** @return Response|ViewModel */
	public function loginAction() {
		$Response = $this->ensureLoggedOut();
		if( !empty( $Response ) ) {
			return $Response;
		}

		$Form = $this->getForm( LoginForm::class );

		if( !$this->getRequest()->isPost() ) {
			$this->PageVisitModelHelper->create( new PageVisit( 'user/login', $_SERVER[ 'REMOTE_ADDR' ], $_SERVER[ 'HTTP_USER_AGENT' ] ) );

			return new ViewModel( [ 'Form' => $Form ] );
		}

		$Response = $this->validateForm( $Form );
		if( !empty( $Response ) ) {
			return $Response;
		}

		/* @var User $User */
		$User     = $Form->getData();
		$Response = $this->authenticateLogin( $User );
		if( !empty( $Response ) ) {
			return $Response;
		}
		$User      = $this->UserModelHelper->read( $User );
		$UserLogin = new UserLogin( $User->getId(), $_SERVER[ 'REMOTE_ADDR' ], $_SERVER[ 'HTTP_USER_AGENT' ] );

		try {
			$this->UserLoginModelHelper->update( $UserLogin, [], [ ULFs::USER_ID => $User->getId(), ULFs::LOGOUT_TIME => null ] );
		} catch( DbOperationHadNoAffectException $e ) {
			// It's ok if there's nothing to update
		}
		$this->UserLoginModelHelper->create( $UserLogin );
		$this->getAuthenticationService()->getStorage()->write( $User );
		$this->getFlashMessenger()->addSuccessMessage( 'Successfully Logged In.' );

		return $this->redirect()->toRoute( 'user' );
	}

	/** @return Response|ViewModel */
	public function logoutAction() {
		$Response = $this->ensureLoggedIn( 'user/login', 'You have to login before you can logout!' );
		if( !empty( $Response ) ) {
			return $Response;
		}

		if( !$this->getRequest()->isPost() ) {
			return $this->redirect()->refresh();
		}

		$Response = $this->validateForm( $this->getForm( LogoutForm::class ) );
		if( !empty( $Response ) ) {
			return $Response;
		}

		/** @var User $User */
		$User      = $this->getAuthenticationService()->getIdentity();
		$UserLogin = $this->UserLoginModelHelper->read( new UserLogin( 0, '', '' ), [ ULFs::USER_ID => $User->getId() ] );
		if( is_array( $UserLogin ) ) {
			$UserLogin = $UserLogin[ 0 ];
		}
		/** @var UserLogin $UserLogin */
		$this->UserLoginModelHelper->update( $UserLogin );

		$this->getAuthenticationService()->clearIdentity();
		$this->getFlashMessenger()->addSuccessMessage( 'Successfully Logged Out.' );

		return $this->redirect()->toRoute( 'user/login' );
	}

	/** @return Response|ViewModel */
	public function signupAction() {
		$Response = $this->ensureLoggedOut();
		if( !empty( $Response ) ) {
			return $Response;
		}

		if( !$this->getRequest()->isPost() ) {
			$this->PageVisitModelHelper->create( new PageVisit( 'user/signup', $_SERVER[ 'REMOTE_ADDR' ], $_SERVER[ 'HTTP_USER_AGENT' ] ) );

			return new ViewModel( [ 'Form' => $this->getForm( SignupForm::class ) ] );
		}

		$data     = $this->getRequest()->getPost();
		$password = $data[ 'password' ];
		$Response = $this->ensureIdentical( $password, $data[ 'verify-password' ] );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$Response = $this->ensurePasswordConstraints( $password );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$Form = $this->getForm( SignupForm::class );

		$Response = $this->validateForm( $Form );
		if( !empty( $Response ) ) {
			return $Response;
		}

		/* @var User $User */
		$User     = $Form->getData();
		$Response = $this->execUserFunc( [ $this->UserModelHelper, 'create' ], $User->setPassword( $password ) );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$this->getFlashMessenger()->addSuccessMessage( "Successfully signed up user: {$User->getUserName()}." );

		return $this->redirect()->toRoute( 'user/login' );
	}

	/** @return Response|ViewModel */
	public function userAction() {
		$Response = $this->ensureLoggedIn( 'user/login', 'You have to be logged in to view user info!' );
		if( !empty( $Response ) ) {
			return $Response;
		}

		/* @var User $User */
		$User      = $this->UserModelHelper->read( $this->getAuthenticationService()->getIdentity() );
		$UserLogin = $this->UserLoginModelHelper->read( new UserLogin( 0, '', '' ), [ ULFs::USER_ID => $User->getId() ] );
		if( is_array( $UserLogin ) && count( $UserLogin ) > 1 ) {
			$UserLogin = $UserLogin[ 1 ];
		} else {
			$UserLogin = null;
		}

		$this->PageVisitModelHelper->create( new PageVisit( 'user', $_SERVER[ 'REMOTE_ADDR' ], $_SERVER[ 'HTTP_USER_AGENT' ], $User->getId() ) );

		return new ViewModel( [ 'Form' => $this->getForm( InfoForm::class ), 'User' => $User, 'UserLogin' => $UserLogin ] );
	}

	/** @return Response|ViewModel */
	public function updateAction() {
		$Response = $this->ensureLoggedIn( 'user/login', 'You must be logged in to update password!' );
		if( !empty( $Response ) ) {
			return $Response;
		}

		/* @var User $User */
		$User = $this->getAuthenticationService()->getIdentity();
		$Form = $this->getForm( UpdateForm::class );
		if( !$this->getRequest()->isPost() ) {
			$this->PageVisitModelHelper->create( new PageVisit( 'user/update', $_SERVER[ 'REMOTE_ADDR' ], $_SERVER[ 'HTTP_USER_AGENT' ], $User->getId() ) );

			return new ViewModel( [ 'Form' => $Form, 'User' => $User ] );
		}

		$data              = $this->getRequest()->getPost();
		$currentPassword   = $data[ 'current_password' ];
		$newPassword       = $data[ 'password' ];
		$verifyNewPassword = $data[ 'verify_new_password' ];

		$Response = $this->ensureIdentical( $newPassword, $verifyNewPassword, 'New passwords are not identical!' );
		if( !empty( $Response ) ) {
			return $Response;
		}
		if( ( new Identical( $newPassword ) )->isValid( $currentPassword ) ) {
			$this->getFlashMessenger()->addErrorMessage( 'Current password and new password must be different!' );

			return $this->redirect()->refresh();
		}

		$Response = $this->ensurePasswordConstraints( $newPassword );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$Response = $this->validateForm( $Form );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$User     = $Form->getData();
		$Response = $this->validatePassword( $User->getUserName(), $currentPassword );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$Response = $this->execUserFunc( [ $this->UserModelHelper, 'update' ], $User->setPassword( $newPassword ) );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$this->getAuthenticationService()->clearIdentity();
		$this->getFlashMessenger()->addSuccessMessage( "Successfully updated password for user: {$User->getUserName()}." );

		return $this->redirect()->toRoute( 'user/login' );
	}
}
