<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210901051639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE notice_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE notice (id INT NOT NULL, target_user_id INT DEFAULT NULL, ыsecond_user_id INT DEFAULT NULL, competition_id INT DEFAULT NULL, game_id INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_480D45C26C066AFE ON notice (target_user_id)');
        $this->addSql('CREATE INDEX IDX_480D45C22793832E ON notice (ыsecond_user_id)');
        $this->addSql('CREATE INDEX IDX_480D45C27B39D312 ON notice (competition_id)');
        $this->addSql('CREATE INDEX IDX_480D45C2E48FD905 ON notice (game_id)');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT FK_480D45C26C066AFE FOREIGN KEY (target_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT FK_480D45C22793832E FOREIGN KEY (ыsecond_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT FK_480D45C27B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT FK_480D45C2E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE notice_id_seq CASCADE');
        $this->addSql('DROP TABLE notice');
    }
}
