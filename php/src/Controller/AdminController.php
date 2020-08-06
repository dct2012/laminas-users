<?php

declare( strict_types = 1 );

namespace App\Controller;

use App\Model\Helper\{AdminLoginHelper, AdminHelper};
use Laminas\Db\Adapter\Adapter;
use Laminas\Session\SessionManager;
use Laminas\Form\FormElementManager\FormElementManagerV3Polyfill as FormManager;

class AdminController extends AbstractHasIdentityController {
	/**
	 * @param Adapter        $db
	 * @param FormManager    $FormManager
	 * @param SessionManager $SessionManager
	 */
	public function __construct( Adapter $db, FormManager $FormManager, SessionManager $SessionManager ) {
		parent::__construct( $db, $FormManager, $SessionManager );
		$this->setHelpers( new AdminHelper( $this->db ), new AdminLoginHelper( $this->db ) );
	}
}
