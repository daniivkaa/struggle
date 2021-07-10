<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210710145345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE rating_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE result_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE rating (id INT NOT NULL, player_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D889262299E6F5DF ON rating (player_id)');
        $this->addSql('CREATE TABLE result (id INT NOT NULL, winer_id INT DEFAULT NULL, loser_id INT DEFAULT NULL, game_id INT DEFAULT NULL, winer_score INT NOT NULL, loser_score INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_136AC113E683ACF4 ON result (winer_id)');
        $this->addSql('CREATE INDEX IDX_136AC1131BCAA5F6 ON result (loser_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_136AC113E48FD905 ON result (game_id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262299E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113E683ACF4 FOREIGN KEY (winer_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1131BCAA5F6 FOREIGN KEY (loser_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player ADD is_active BOOLEAN NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE rating_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE result_id_seq CASCADE');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE result');
        $this->addSql('ALTER TABLE player DROP is_active');
    }
}
