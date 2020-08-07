<?php

declare( strict_types = 1 );

namespace App\Enum;

class Routes extends AbstractEnum {
	const USER        = 'user';
	const USER_DELETE = 'user/delete';
	const USER_LOGIN  = 'user/login';
	const USER_LOGOUT = 'user/logout';
	const USER_SIGNUP = 'user/signup';
	const USER_UPDATE = 'user/update';

	const ADMIN         = 'admin';
	const ADMIN_LOGIN   = 'admin/login';
	const ADMIN_LOGOUT  = 'admin/logout';
	const ADMIN_SITEMAP = 'admin/sitemap';
}