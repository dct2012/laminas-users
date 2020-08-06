<?php

declare( strict_types = 1 );

namespace App\Controller;

use App\Enum\Routes;
use Laminas\Http\Response;
use Laminas\Db\Adapter\Adapter;
use Laminas\Session\SessionManager;
use Laminas\Form\FormElementManager\FormElementManagerV3Polyfill as FormManager;

class IndexController extends AbstractController {
	/**
	 * @param Adapter        $db
	 * @param FormManager    $FormManager
	 * @param SessionManager $SessionManager
	 */
	public function __construct( Adapter $db, FormManager $FormManager, SessionManager $SessionManager ) {
		parent::__construct( $db, $FormManager, $SessionManager );
	}

	/** @return Response */
	public function indexAction(): Response {
		return $this->redirect()->toRoute( $this->getAuthenticationService()->hasIdentity()
			? Routes::USER
			: Routes::USER_LOGIN );
	}
}
