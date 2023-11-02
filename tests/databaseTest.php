<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class databaseTest extends TestCase
{

	public function testConnection(): void
	{
		if (!extension_loaded('pgsql')) {
			$this->markTestSkipped(
				'The PostgreSQL extension is not available',
			);
		}
		$this->markTestIncomplete(
			'This test has not been implemented yet.',
		);
	}
	// connect to db
	// assert exception on Syntaxerror or sth?
}
