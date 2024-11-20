<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240723093201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('SET FOREIGN_KEY_CHECKS = 0;');
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY IF EXISTS FK_505865971A445520;');
        $this->addSql('SET FOREIGN_KEY_CHECKS = 1;');

        // Drop the index if it exists
        $this->addSql('DROP INDEX IF EXISTS IDX_505865976B3CA4B ON tasks;');

        // Modify the column
        $this->addSql('ALTER TABLE tasks CHANGE id_user id_user INT NOT NULL');

        // Add the new index
        $this->addSql('CREATE INDEX IDX_505865976B3CA4B ON tasks (id_user)');

        // Add the foreign key constraint
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_505865971A445520 FOREIGN KEY (id_user) REFERENCES user (id)');

      
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tasks_user (tasks_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_677C783FE3272D31 (tasks_id), INDEX IDX_677C783FA76ED395 (user_id), PRIMARY KEY(tasks_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tasks_user ADD CONSTRAINT FK_677C783FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tasks_user ADD CONSTRAINT FK_677C783FE3272D31 FOREIGN KEY (tasks_id) REFERENCES tasks (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_505865976B3CA4B');
        $this->addSql('ALTER TABLE tasks CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('DROP INDEX idx_505865976b3ca4b ON tasks');
        $this->addSql('CREATE INDEX FK_505865971A445520 ON tasks (id_user)');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_505865976B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
    }
}
