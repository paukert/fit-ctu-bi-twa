<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220120105406 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Add few testing data';
	}

	public function up(Schema $schema): void
	{
		$this->addSql("INSERT INTO employee (id, first_name, last_name, mail, phone, description, web) VALUES (nextval('employee_id_seq'), 'Lukáš', 'Paukert', 'paukeluk@fit.cvut.cz', '+420 777 888 999', 'Description v1', NULL)");
		$this->addSql("INSERT INTO employee (id, first_name, last_name, mail, phone, description, web) VALUES (nextval('employee_id_seq'), 'Tomáš', 'Novák', 'novaktom@fit.cvut.cz', '+420 555 444 333', 'Description v2', 'https://fit.cvut.cz/cs')");

		// Password is same as the username
		$this->addSql("INSERT INTO account (id, owner_id, username, roles, password, valid_to) VALUES (nextval('account_id_seq'), 1, 'paukeluk', '[\"ROLE_ADMIN\"]','$2y$13\$oOXeUnY/NKgkEt1QqFpcIO31TZjd.rtnNUWdWDdIuSUHaQr6Xk1Hm', NULL)");
		$this->addSql("INSERT INTO account (id, owner_id, username, roles, password, valid_to) VALUES (nextval('account_id_seq'), 1, 'paukeluk2', '[]', '$2y$13\$xVOLp8JeG7Cg9ku5ZigtyeHGd.TL2jq7.kBP8cG5BSkC9beMhyK7i', '2022-04-01')");
		$this->addSql("INSERT INTO account (id, owner_id, username, roles, password, valid_to) VALUES (nextval('account_id_seq'), 2, 'novaktom', '[]', '$2y$13$9yNBUfUiNSkqEnNRGu5YAeZZnp/U/aV3DHBgleQ3rPdF.Mx/oAFq2', NULL)");

		$this->addSql("INSERT INTO role (id, description, title, is_visible) VALUES (nextval('role_id_seq'), 'Fakulta informačních technologií - pracovník', 'FIT - P', true)");
		$this->addSql("INSERT INTO role (id, description, title, is_visible) VALUES (nextval('role_id_seq'), 'Fakulta informačních technologií - student', 'FIT - S', true)");

		$this->addSql("INSERT INTO employee_roles (employee_id, role_id) VALUES (1, 1)");
		$this->addSql("INSERT INTO employee_roles (employee_id, role_id) VALUES (2, 1)");
		$this->addSql("INSERT INTO employee_roles (employee_id, role_id) VALUES (2, 2)");
	}

	public function down(Schema $schema): void
	{
		// this down() migration is auto-generated, please modify it to your needs
	}
}
