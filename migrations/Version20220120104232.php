<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220120104232 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Initial PostgreSQL migration';
	}

	public function up(Schema $schema): void
	{
		$this->addSql('CREATE SEQUENCE account_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
		$this->addSql('CREATE SEQUENCE employee_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
		$this->addSql('CREATE SEQUENCE role_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
		$this->addSql('CREATE TABLE account (id INT NOT NULL, owner_id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, valid_to TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
		$this->addSql('CREATE UNIQUE INDEX UNIQ_ACCOUNT_USERNAME ON account (username)');
		$this->addSql('CREATE INDEX IDX_ACCOUNT_OWNER_ID ON account (owner_id)');
		$this->addSql('CREATE TABLE employee (id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, mail VARCHAR(255) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, description VARCHAR(2000) DEFAULT NULL, web VARCHAR(500) DEFAULT NULL, image_file_name VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id))');
		$this->addSql('CREATE TABLE employee_roles (employee_id INT NOT NULL, role_id INT NOT NULL, PRIMARY KEY(employee_id, role_id))');
		$this->addSql('CREATE INDEX IDX_EMPLOYEE_ROLES_EMPLOYEE_ID ON employee_roles (employee_id)');
		$this->addSql('CREATE INDEX IDX_EMPLOYEE_ROLES_ROLE_ID ON employee_roles (role_id)');
		$this->addSql('CREATE TABLE role (id INT NOT NULL, description VARCHAR(255) NOT NULL, title VARCHAR(100) NOT NULL, is_visible BOOLEAN NOT NULL, PRIMARY KEY(id))');
		$this->addSql('ALTER TABLE account ADD CONSTRAINT FK_ACCOUNT_OWNER_ID FOREIGN KEY (owner_id) REFERENCES employee (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE employee_roles ADD CONSTRAINT FK_EMPLOYEE_ROLES_EMPLOYEE_ID FOREIGN KEY (employee_id) REFERENCES employee (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE employee_roles ADD CONSTRAINT FK_EMPLOYEE_ROLES_ROLE_ID FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
	}

	public function down(Schema $schema): void
	{
		$this->addSql('ALTER TABLE account DROP CONSTRAINT FK_ACCOUNT_OWNER_ID');
		$this->addSql('ALTER TABLE employee_roles DROP CONSTRAINT FK_EMPLOYEE_ROLES_EMPLOYEE_ID');
		$this->addSql('ALTER TABLE employee_roles DROP CONSTRAINT FK_EMPLOYEE_ROLES_ROLE_ID');
		$this->addSql('DROP SEQUENCE account_id_seq CASCADE');
		$this->addSql('DROP SEQUENCE employee_id_seq CASCADE');
		$this->addSql('DROP SEQUENCE role_id_seq CASCADE');
		$this->addSql('DROP TABLE account');
		$this->addSql('DROP TABLE employee');
		$this->addSql('DROP TABLE employee_roles');
		$this->addSql('DROP TABLE role');
	}
}
