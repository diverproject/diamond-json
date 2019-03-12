<?php

declare(strict_types=1);

namespace test\diamond\json;

use PHPUnit\Framework\TestCase;
use diamond\lang\Diamond;
use diamond\lang\System;
use diamond\lang\utils\GlobalFunctions;

abstract class DiamondJsonTest extends TestCase
{
	protected const ARCHITECTURE_NOT_FOUND = 'was not possible detect system architecture';
	protected $architecture;

	public function __construct()
	{
		GlobalFunctions::load();
		Diamond::setEnvironment(Diamond::ENVIRONMENT_TEST_CASE);
		Diamond::setEnabledParseThrows(false);

		parent::__construct(nameOf($this));

		$this->architecture = System::getArchitecture();
	}
}

