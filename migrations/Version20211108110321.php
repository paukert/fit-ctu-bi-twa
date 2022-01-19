<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211108110321 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Initial migration';
	}

	public function up(Schema $schema): void
	{
		$this->addSql('CREATE TABLE employee (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, mail VARCHAR(255) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, description VARCHAR(2000) DEFAULT NULL, web VARCHAR(500) DEFAULT NULL)');
		$this->addSql('CREATE TABLE account (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, valid_to DATETIME DEFAULT NULL, FOREIGN KEY (owner_id) REFERENCES employee (id))');
		$this->addSql('CREATE INDEX IDX_ACCOUNT_OWNER_ID ON account (owner_id)');
		$this->addSql('CREATE TABLE role (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, description VARCHAR(255) NOT NULL, title VARCHAR(100) NOT NULL, is_visible BOOLEAN NOT NULL)');
		$this->addSql('CREATE TABLE employee_roles (employee_id INTEGER NOT NULL, role_id INTEGER NOT NULL, PRIMARY KEY(employee_id, role_id), FOREIGN KEY (employee_id) REFERENCES employee (id), FOREIGN KEY (role_id) REFERENCES role (id))');
		$this->addSql('CREATE INDEX IDX_EMPLOYEE_ROLES_EMPLOYEE_ID ON employee_roles (employee_id)');
		$this->addSql('CREATE INDEX IDX_EMPLOYEE_ROLES_ROLE_ID ON employee_roles (role_id)');

		// Add few testing data
		$this->addSql("INSERT INTO employee (first_name, last_name, mail, phone, description) VALUES ('Lukáš', 'Paukert', 'paukeluk@fit.cvut.cz', '+420 777 888 999', 'Description v1')");
		$this->addSql("INSERT INTO employee (first_name, last_name, mail, phone, description) VALUES ('Tomáš', 'Novák', 'novaktom@fit.cvut.cz', '+420 555 444 333', 'Description v2')");

		$this->addSql("INSERT INTO account (owner_id, username, password) VALUES (1, 'paukeluk', 'pwpaukeluk')");
		$this->addSql("INSERT INTO account (owner_id, username, password) VALUES (1, 'paukeluk2', 'pwpaukeluk2')");
		$this->addSql("INSERT INTO account (owner_id, username, password) VALUES (2, 'novaktom', 'pwnovaktom')");

		$this->addSql("INSERT INTO role (description, title, is_visible) VALUES ('Fakulta informačních technologií - pracovník', 'FIT - P', true)");
		$this->addSql("INSERT INTO role (description, title, is_visible) VALUES ('Fakulta informačních technologií - student', 'FIT - S', true)");

		$this->addSql("INSERT INTO employee_roles (employee_id, role_id) VALUES (1, 1)");
		$this->addSql("INSERT INTO employee_roles (employee_id, role_id) VALUES (2, 1)");
		$this->addSql("INSERT INTO employee_roles (employee_id, role_id) VALUES (2, 2)");
	}

	public function down(Schema $schema): void
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->addSql('DROP TABLE account');
		$this->addSql('DROP TABLE employee');
		$this->addSql('DROP TABLE employee_roles');
		$this->addSql('DROP TABLE role');
	}
}
