<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211216000000 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE users (
            id INT AUTO_INCREMENT NOT NULL,
            email VARCHAR(50) NOT NULL UNIQUE,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE projects (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(150) NOT NULL,
            description TEXT NOT NULL,
            start_date DATE NOT NULL,
            end_date DATE NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE project_user (
            project_id INT NOT NULL,
            user_id INT NOT NULL,
            FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE tasks (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(150) NOT NULL,
            description TEXT NOT NULL,
            start_date DATE NOT NULL,
            end_date DATE NOT NULL,
            project_id INT NOT NULL,
            PRIMARY KEY(id),
            FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tasks');
        $this->addSql('DROP TABLE project_user');
        $this->addSql('DROP TABLE projects');
        $this->addSql('DROP TABLE users');
    }
}
