<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210710172119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE players_game_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE players_game (id INT NOT NULL, target_player_id INT DEFAULT NULL, second_player_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B112FF9CAD5287F3 ON players_game (target_player_id)');
        $this->addSql('CREATE INDEX IDX_B112FF9CA40D7457 ON players_game (second_player_id)');
        $this->addSql('ALTER TABLE players_game ADD CONSTRAINT FK_B112FF9CAD5287F3 FOREIGN KEY (target_player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE players_game ADD CONSTRAINT FK_B112FF9CA40D7457 FOREIGN KEY (second_player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE players_game_id_seq CASCADE');
        $this->addSql('DROP TABLE players_game');
    }
}
