<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251019174052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create user table';
    }

    // migrations/Version...php (extrait de la méthode up())

    public function up(Schema $schema): void
    {
        // Crée la table 'user'
        $this->addSql('CREATE TABLE "user" (
        id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
        email VARCHAR(180) NOT NULL,
        roles TEXT NOT NULL,
        password VARCHAR(255) NOT NULL,


        first_name VARCHAR(255) NOT NULL,
        last_name VARCHAR(255) NOT NULL,
        job_title VARCHAR(255) DEFAULT NULL,
        employee_id VARCHAR(50) DEFAULT NULL,
        photo_path VARCHAR(255) DEFAULT NULL,


        department VARCHAR(100) DEFAULT NULL,
        team VARCHAR(100) DEFAULT NULL,
        manager_id INTEGER DEFAULT NULL,


        capacity_hours DECIMAL(4, 2) NOT NULL,
        is_active BOOLEAN NOT NULL,


        last_login DATETIME DEFAULT NULL
    )');

    // Ajoutez l'index pour l'email (doit être unique)
    $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');

    // NOTE : Les relations N-à-N (Compétences) et les relations (Manager N+1) seront ajoutées plus tard
    // via des tables séparées pour plus de clarté et de respect des bonnes pratiques.
}

    public function down(Schema $schema): void
    {
        // Annulation de la migration
        $this->addSql('DROP TABLE "user"');
    }
}
