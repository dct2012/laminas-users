<?php

declare( strict_types = 1 );

namespace App\Controller;

use App\Enum\Routes;
use App\Model\{Identity, User};
use App\Model\Values\IdentityFields as IFs;
use App\Model\Helper\{UserLoginHelper, UserHelper};
use App\Form\User\{DeleteForm, SignupForm, UpdateForm};
use Laminas\Http\Response;
use Laminas\Db\Adapter\Adapter;
use Laminas\Validator\Identical;
use Laminas\View\Model\ViewModel;
use Laminas\Session\SessionManager;
use Laminas\Form\FormElementManager\FormElementManagerV3Polyfill as FormManager;

class UserController extends AbstractHasIdentityController {
	/**
	 * @param Adapter        $db
	 * @param FormManager    $FormManager
	 * @param SessionManager $SessionManager
	 */
	public function __construct( Adapter $db, FormManager $FormManager, SessionManager $SessionManager ) {
		parent::__construct( $db, $FormManager, $SessionManager );
		$this->setHelpers( new UserHelper( $db ), new UserLoginHelper( $db ) );
	}

	/** @return Response|ViewModel */
	public function deleteAction() {
		$Response = $this->ensureLoggedIn( Routes::USER_LOGIN, 'You must be logged in to delete your account!' );
		if( !empty( $Response ) ) {
			return $Response;
		}
		/** @var User $User */
		$User = $this->getAuthenticationService()->getIdentity();
		$Form = $this->getForm( DeleteForm::class );
		if( !$this->getRequest()->isPost() ) {
			$this->PageVisitHelper->create( $this->createPageVisit( Routes::USER_DELETE ) );

			return new ViewModel( [ 'Form' => $Form, 'User' => $User ] );
		}
		$Response = $this->validateForm( $Form );
		if( !empty( $Response ) ) {
			return $Response;
		}
		$Response = $this->validatePassword( $User->getName(), $Form->getData()[ IFs::PASSWORD ] );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$User = $this->ModelHelper->delete( $User, [ IFs::ID => $User->getIdentityId() ] );
		$this->getAuthenticationService()->clearIdentity();
		$this->getFlashMessenger()->addSuccessMessage( "Successfully deleted account: {$User->getName()}." );

		return $this->redirect()->toRoute( Routes::USER_LOGIN );
	}

	/** @return Response|ViewModel */
	public function signupAction() {
		$Response = $this->ensureLoggedOut();
		if( !empty( $Response ) ) {
			return $Response;
		}
		$Form = $this->getForm( SignupForm::class );
		if( !$this->getRequest()->isPost() ) {
			$this->PageVisitHelper->create( $this->createPageVisit( Routes::USER_SIGNUP ) );

			return new ViewModel( [ 'Form' => $Form ] );
		}
		$Response = $this->validateForm( $Form );
		if( !empty( $Response ) ) {
			return $Response;
		}
		$data     = $Form->getData();
		$password = $data[ IFs::PASSWORD ];
		$Response = $this->ensureIdentical( $password, $data[ SignupForm::VERIFY_PASSWORD ] );
		if( !empty( $Response ) ) {
			return $Response;
		}
		$Response = $this->ensurePasswordConstraints( $password );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$User = $this->ModelHelper->create( new User( $this->IdentityHelper->create( new Identity( $data[ IFs::NAME ], $password ) ) ) );
		$this->getFlashMessenger()->addSuccessMessage( "Successfully signed up user: {$User->getName()}." );

		return $this->redirect()->toRoute( Routes::USER_LOGIN );
	}

	/** @return Response|ViewModel */
	public function updateAction() {
		$Response = $this->ensureLoggedIn( Routes::USER_LOGIN, 'You must be logged in to update password!' );
		if( !empty( $Response ) ) {
			return $Response;
		}
		/* @var User $User */
		$User = $this->getAuthenticationService()->getIdentity();
		$Form = $this->getForm( UpdateForm::class );
		if( !$this->getRequest()->isPost() ) {
			$this->PageVisitHelper->create( $this->createPageVisit( Routes::USER_UPDATE ) );

			return new ViewModel( [ 'Form' => $Form, 'User' => $User ] );
		}
		$Response = $this->validateForm( $Form );
		if( !empty( $Response ) ) {
			return $Response;
		}
		$data              = $Form->getData();
		$currentPassword   = $data[ UpdateForm::CURRENT_PASSWORD ];
		$newPassword       = $data[ IFs::PASSWORD ];
		$verifyNewPassword = $data[ UpdateForm::VERIFY_NEW_PASSWORD ];
		$Response          = $this->ensureIdentical( $newPassword, $verifyNewPassword, 'New passwords are not identical!' );
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
		$Response = $this->validatePassword( $User->getName(), $currentPassword );
		if( !empty( $Response ) ) {
			return $Response;
		}

		$User = $User->setIdentity( $this->IdentityHelper->update( $User->setPassword( $newPassword )->getIdentity() ) );
		$this->getAuthenticationService()->clearIdentity();
		$this->getFlashMessenger()->addSuccessMessage( "Successfully updated password for user: {$User->getName()}." );

		return $this->redirect()->toRoute( Routes::USER_LOGIN );
	}
}
