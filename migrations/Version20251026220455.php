<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251026220455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password, first_name, last_name, job_title, employee_id, photo_path, department, team, manager_id, capacity_hours, is_active, last_login FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, manager_id INTEGER DEFAULT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, job_title VARCHAR(255) DEFAULT NULL, employee_id VARCHAR(50) DEFAULT NULL, photo_path VARCHAR(255) DEFAULT NULL, department VARCHAR(100) DEFAULT NULL, team VARCHAR(100) DEFAULT NULL, capacity_hours NUMERIC(4, 2) DEFAULT \'40\' NOT NULL, is_active BOOLEAN NOT NULL, last_login VARCHAR(255) DEFAULT NULL, date_of_birth DATE DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, message CLOB DEFAULT NULL, skills CLOB DEFAULT NULL --(DC2Type:json)
        , creation_date DATETIME DEFAULT NULL, CONSTRAINT FK_8D93D649783E3463 FOREIGN KEY (manager_id) REFERENCES user (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user (id, email, roles, password, first_name, last_name, job_title, employee_id, photo_path, department, team, manager_id, capacity_hours, is_active, last_login) SELECT id, email, roles, password, first_name, last_name, job_title, employee_id, photo_path, department, team, manager_id, capacity_hours, is_active, last_login FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6498C03F15C ON user (employee_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649783E3463 ON user (manager_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, manager_id, email, roles, password, first_name, last_name, job_title, employee_id, photo_path, department, team, capacity_hours, is_active, last_login FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, manager_id INTEGER DEFAULT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, job_title VARCHAR(255) DEFAULT NULL, employee_id VARCHAR(50) DEFAULT NULL, photo_path VARCHAR(255) DEFAULT NULL, department VARCHAR(100) DEFAULT NULL, team VARCHAR(100) DEFAULT NULL, capacity_hours NUMERIC(4, 2) NOT NULL, is_active BOOLEAN NOT NULL, last_login DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO user (id, manager_id, email, roles, password, first_name, last_name, job_title, employee_id, photo_path, department, team, capacity_hours, is_active, last_login) SELECT id, manager_id, email, roles, password, first_name, last_name, job_title, employee_id, photo_path, department, team, capacity_hours, is_active, last_login FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }
}
