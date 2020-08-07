<?php

declare( strict_types = 1 );

namespace App\Command\Admin;

use Exception, RuntimeException;
use App\Functions as F;
use App\Model\{Admin, Identity};
use App\Model\Values\IdentityFields as IFs;
use App\Model\Helper\{AdminHelper, IdentityHelper};
use Laminas\Db\Adapter\AdapterInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\{InputArgument, InputInterface};

class AdminCreateCommand extends Command {
	/** @var AdapterInterface */
	protected AdapterInterface $db;
	/** @var AdminHelper */
	protected AdminHelper $AdminHelper;
	/** @var IdentityHelper */
	protected IdentityHelper $IdentityHelper;

	/** @param AdapterInterface $db */
	public function __construct( AdapterInterface $db ) {
		parent::__construct( 'admin:create' );
		$this->db             = $db;
		$this->AdminHelper    = new AdminHelper( $this->db );
		$this->IdentityHelper = new IdentityHelper( $this->db );
	}

	protected function configure() {
		$this->setDescription( 'Creates a new admin.' )
		     ->setHelp( 'This command allows you to create a admin...' )
		     ->addArgument( IFs::NAME, InputArgument::REQUIRED, 'Admin Name' )
		     ->addArgument( IFs::PASSWORD, InputArgument::REQUIRED, 'Admin Password' );
	}

	/**
	 * @param Identity $Identity
	 *
	 * @return $this
	 */
	protected function assertIdentityDoesntExist( Identity $Identity ): self {
		$name     = $Identity->getName();
		$Identity = $this->IdentityHelper->read( $Identity, [ IFs::NAME => $name ] );
		if( !empty( $Identity ) && !empty( $Identity->getId() ) ) {
			throw new RuntimeException( "Identity with name '{$name}' already exists!" );
		}

		return $this;
	}

	/**
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 *
	 * @return int
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		try {
			$name     = $input->getArgument( IFs::NAME );
			$password = $input->getArgument( IFs::PASSWORD );

			$output->writeln( "<info>".F::createTimeStamp()."</info>|INFO|<comment>Creating admin with name '{$name}' and password: '{$password}'.</comment>" );
			$Identity = new Identity( $name, $password );

			$output->writeln( "<info>".F::createTimeStamp()."</info>|INFO|<comment>Checking if a identity with name '{$name}' already exists.</comment> " );
			$this->assertIdentityDoesntExist( $Identity );

			$output->writeln( "<info>".F::createTimeStamp()."</info>|INFO|<comment>Checking if password '{$password}' meets requirements.</comment> " );
			F::assertPasswordLength( $password );

			$output->writeln( "<info>".F::createTimeStamp()."</info>|INFO|<comment>Creating Identity.</comment>" );
			$Identity = $this->IdentityHelper->create( $Identity );

			$output->writeln( "<info>".F::createTimeStamp()."</info>|INFO|<comment>Creating Admin.</comment>" );
			$Admin = $this->AdminHelper->create( new Admin( $Identity ) );

			if( empty( $Admin->getId() ) ) {
				throw new Exception( "Failed to create Identity #{$Identity->getId()} Admin! No id received!" );
			}

			$output->writeln( "<info>".F::createTimeStamp()."</info>|SUCCESS|<question>Successfully created Admin #{$Admin->getId()}.</question>" );
		} catch( Exception $e ) {
			$output->writeln( "<info>".F::createTimeStamp()."</info>|ERROR|<error>{$e->getMessage()}</error>" );

			return Command::FAILURE;
		}

		return Command::SUCCESS;
	}
}