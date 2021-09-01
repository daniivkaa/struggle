<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210629063742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game ADD winer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game ADD is_active BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CE683ACF4 FOREIGN KEY (winer_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_232B318CE683ACF4 ON game (winer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318CE683ACF4');
        $this->addSql('DROP INDEX IDX_232B318CE683ACF4');
        $this->addSql('ALTER TABLE game DROP winer_id');
        $this->addSql('ALTER TABLE game DROP is_active');
    }
}
