<?php

declare( strict_types = 1 );

namespace App\Controller;

use Exception;
use App\Enum\Routes;
use App\Model\{Identity, User};
use App\Model\Values\IdentityFields as IFs;
use App\Model\Helper\{UserLoginHelper, UserHelper};
use App\Form\User\{DeleteForm, SignupForm, UpdateForm};
use Laminas\Http\Response;
use Laminas\Db\Adapter\Adapter;
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
		try {
			$this->assertLoggedIn( 'You must be logged in to delete your account!' );

			/** @var User $User */
			$User = $this->getAuthenticationService()->getIdentity();
			$Form = $this->getForm( DeleteForm::class );

			if( $this->getRequest()->isPost() ) {
				$this->validateForm( $Form )->validatePassword( $User->getName(), $Form->getData()[ IFs::PASSWORD ] );

				$User = $this->ModelHelper->delete( $User, [ IFs::ID => $User->getIdentityId() ] );
				$this->getAuthenticationService()->clearIdentity();
				$this->getFlashMessenger()->addSuccessMessage( "Successfully deleted account: {$User->getName()}." );
			} else {
				$this->PageVisitHelper->create( $this->createPageVisit( Routes::USER_DELETE ) );

				return new ViewModel( [ 'Form' => $Form, 'User' => $User ] );
			}
		} catch( Exception $e ) {
			$this->getFlashMessenger()->addErrorMessage( $e->getMessage() );
		}

		return $this->redirect()->toRoute( Routes::USER_LOGIN );
	}

	/** @return Response|ViewModel */
	public function signupAction() {
		try {
			$this->assertLoggedOut();

			$Form = $this->getForm( SignupForm::class );

			if( $this->getRequest()->isPost() ) {
				$this->validateForm( $Form );
				$data     = $Form->getData();
				$password = $data[ IFs::PASSWORD ];

				$this->assertIdentical( $password, $data[ SignupForm::VERIFY_PASSWORD ] )
				     ->assertPasswordLength( $password );

				$User = $this->ModelHelper->create( new User( $this->IdentityHelper->create( new Identity( $data[ IFs::NAME ], $password ) ) ) );
				$this->getFlashMessenger()->addSuccessMessage( "Successfully signed up user: {$User->getName()}." );
			} else {
				$this->PageVisitHelper->create( $this->createPageVisit( Routes::USER_SIGNUP ) );

				return new ViewModel( [ 'Form' => $Form ] );
			}
		} catch( Exception $e ) {
			$this->getFlashMessenger()->addErrorMessage( $e->getMessage() );
		}

		return $this->redirect()->toRoute( Routes::USER_LOGIN );
	}

	/** @return Response|ViewModel */
	public function updateAction() {
		try {
			$this->assertLoggedIn( 'You must be logged in to update password!' );

			/* @var User $User */
			$User = $this->getAuthenticationService()->getIdentity();
			$Form = $this->getForm( UpdateForm::class );

			if( $this->getRequest()->isPost() ) {
				$this->validateForm( $Form );

				$data              = $Form->getData();
				$currentPassword   = $data[ UpdateForm::CURRENT_PASSWORD ];
				$newPassword       = $data[ IFs::PASSWORD ];
				$verifyNewPassword = $data[ UpdateForm::VERIFY_NEW_PASSWORD ];

				$this->assertIdentical( $newPassword, $verifyNewPassword )
				     ->assertNotIdentical( $currentPassword, $newPassword, 'Current password and new password must be different!' )
				     ->assertPasswordLength( $newPassword )
				     ->validatePassword( $User->getName(), $currentPassword );

				$User = $User->setIdentity( $this->IdentityHelper->update( $User->setPassword( $newPassword )->getIdentity() ) );
				$this->getAuthenticationService()->clearIdentity();
				$this->getFlashMessenger()->addSuccessMessage( "Successfully updated password for user: {$User->getName()}." );
			} else {
				$this->PageVisitHelper->create( $this->createPageVisit( Routes::USER_UPDATE ) );

				return new ViewModel( [ 'Form' => $Form, 'User' => $User ] );
			}
		} catch( Exception $e ) {
			$this->getFlashMessenger()->addErrorMessage( $e->getMessage() );
		}

		return $this->redirect()->toRoute( Routes::USER_LOGIN );
	}
}
