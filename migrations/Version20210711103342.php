<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210711103342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE result DROP CONSTRAINT fk_136ac113e683acf4');
        $this->addSql('ALTER TABLE result DROP CONSTRAINT fk_136ac1131bcaa5f6');
        $this->addSql('ALTER TABLE result DROP CONSTRAINT fk_136ac113e48fd905');
        $this->addSql('DROP INDEX uniq_136ac113e48fd905');
        $this->addSql('DROP INDEX idx_136ac113e683acf4');
        $this->addSql('DROP INDEX idx_136ac1131bcaa5f6');
        $this->addSql('ALTER TABLE result DROP winer_id');
        $this->addSql('ALTER TABLE result DROP loser_id');
        $this->addSql('ALTER TABLE result DROP game_id');
        $this->addSql('ALTER TABLE result DROP winer_score');
        $this->addSql('ALTER TABLE result DROP loser_score');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE result ADD winer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE result ADD loser_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE result ADD game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE result ADD winer_score INT NOT NULL');
        $this->addSql('ALTER TABLE result ADD loser_score INT NOT NULL');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT fk_136ac113e683acf4 FOREIGN KEY (winer_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT fk_136ac1131bcaa5f6 FOREIGN KEY (loser_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT fk_136ac113e48fd905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_136ac113e48fd905 ON result (game_id)');
        $this->addSql('CREATE INDEX idx_136ac113e683acf4 ON result (winer_id)');
        $this->addSql('CREATE INDEX idx_136ac1131bcaa5f6 ON result (loser_id)');
    }
}
