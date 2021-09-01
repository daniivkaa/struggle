<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210901054943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notice DROP CONSTRAINT fk_480d45c22793832e');
        $this->addSql('DROP INDEX idx_480d45c22793832e');
        $this->addSql('ALTER TABLE notice RENAME COLUMN ыsecond_user_id TO second_user_id');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT FK_480D45C2B02C53F8 FOREIGN KEY (second_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_480D45C2B02C53F8 ON notice (second_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE notice DROP CONSTRAINT FK_480D45C2B02C53F8');
        $this->addSql('DROP INDEX IDX_480D45C2B02C53F8');
        $this->addSql('ALTER TABLE notice RENAME COLUMN second_user_id TO "ыsecond_user_id"');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT fk_480d45c22793832e FOREIGN KEY ("ыsecond_user_id") REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_480d45c22793832e ON notice (ыsecond_user_id)');
    }
}
