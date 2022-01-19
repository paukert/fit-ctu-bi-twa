<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211114161902 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Add image_file_name column to table employee';
	}

	public function up(Schema $schema): void
	{
		$employeeTable = $schema->getTable('employee');
		$employeeTable->addColumn('image_file_name', 'string', ['length' => 50, 'default' => NULL, 'notnull' => false]);
	}

	public function down(Schema $schema): void
	{
		$employeeTable = $schema->getTable('employee');
		$employeeTable->dropColumn('image_file_name');
	}
}
