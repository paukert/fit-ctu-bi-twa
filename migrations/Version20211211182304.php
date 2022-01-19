<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211211182304 extends AbstractMigration
{
	public function getDescription(): string
	{
		return '';
	}

	public function up(Schema $schema): void
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->addSql('DROP INDEX IDX_ACCOUNT_OWNER_ID');
		$this->addSql('CREATE TEMPORARY TABLE __temp__account AS SELECT id, owner_id, username, password, valid_to FROM account');
		$this->addSql('DROP TABLE account');
		$this->addSql('CREATE TABLE account (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, username VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL COLLATE BINARY, valid_to DATETIME DEFAULT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , CONSTRAINT FK_ACCOUNT_OWNER_ID FOREIGN KEY (owner_id) REFERENCES employee (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
		$this->addSql('INSERT INTO account (id, owner_id, username, password, valid_to, roles) SELECT id, owner_id, username, password, valid_to, \'ROLE_USER\' FROM __temp__account');
		$this->addSql('DROP TABLE __temp__account');
		$this->addSql('CREATE UNIQUE INDEX UNIQ_ACCOUNT_USERNAME ON account (username)');
		$this->addSql('CREATE INDEX IDX_ACCOUNT_OWNER_ID ON account (owner_id)');
		$this->addSql('DROP INDEX IDX_EMPLOYEE_ROLES_ROLE_ID');
		$this->addSql('DROP INDEX IDX_EMPLOYEE_ROLES_EMPLOYEE_ID');
		$this->addSql('CREATE TEMPORARY TABLE __temp__employee_roles AS SELECT employee_id, role_id FROM employee_roles');
		$this->addSql('DROP TABLE employee_roles');
		$this->addSql('CREATE TABLE employee_roles (employee_id INTEGER NOT NULL, role_id INTEGER NOT NULL, PRIMARY KEY(employee_id, role_id), CONSTRAINT FK_EMPLOYEE_ROLES_EMPLOYEE_ID FOREIGN KEY (employee_id) REFERENCES employee (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_EMPLOYEE_ROLES_ROLE_ID FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
		$this->addSql('INSERT INTO employee_roles (employee_id, role_id) SELECT employee_id, role_id FROM __temp__employee_roles');
		$this->addSql('DROP TABLE __temp__employee_roles');
		$this->addSql('CREATE INDEX IDX_EMPLOYEE_ROLES_EMPLOYEE_ID ON employee_roles (employee_id)');
		$this->addSql('CREATE INDEX IDX_EMPLOYEE_ROLES_ROLE_ID ON employee_roles (role_id)');
	}

	public function down(Schema $schema): void
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->addSql('DROP INDEX UNIQ_ACCOUNT_USERNAME');
		$this->addSql('DROP INDEX IDX_ACCOUNT_OWNER_ID');
		$this->addSql('CREATE TEMPORARY TABLE __temp__account AS SELECT id, owner_id, username, password, valid_to FROM account');
		$this->addSql('DROP TABLE account');
		$this->addSql('CREATE TABLE account (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, username VARCHAR(255) NOT NULL COLLATE BINARY, password VARCHAR(255) NOT NULL, valid_to DATETIME DEFAULT NULL)');
		$this->addSql('INSERT INTO account (id, owner_id, username, password, valid_to) SELECT id, owner_id, username, password, valid_to FROM __temp__account');
		$this->addSql('DROP TABLE __temp__account');
		$this->addSql('CREATE INDEX IDX_ACCOUNT_OWNER_ID ON account (owner_id)');
		$this->addSql('DROP INDEX IDX_EMPLOYEE_ROLES_EMPLOYEE_ID');
		$this->addSql('DROP INDEX IDX_EMPLOYEE_ROLES_ROLE_ID');
		$this->addSql('CREATE TEMPORARY TABLE __temp__employee_roles AS SELECT employee_id, role_id FROM employee_roles');
		$this->addSql('DROP TABLE employee_roles');
		$this->addSql('CREATE TABLE employee_roles (employee_id INTEGER NOT NULL, role_id INTEGER NOT NULL, PRIMARY KEY(employee_id, role_id))');
		$this->addSql('INSERT INTO employee_roles (employee_id, role_id) SELECT employee_id, role_id FROM __temp__employee_roles');
		$this->addSql('DROP TABLE __temp__employee_roles');
		$this->addSql('CREATE INDEX IDX_EMPLOYEE_ROLES_ROLE_ID ON employee_roles (role_id)');
		$this->addSql('CREATE INDEX IDX_EMPLOYEE_ROLES_EMPLOYEE_ID ON employee_roles (employee_id)');
	}
}
