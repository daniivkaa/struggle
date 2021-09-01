<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210811044436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE players_game ADD competition_id INT NOT NULL');
        $this->addSql('ALTER TABLE players_game ADD game_id INT NOT NULL');
        $this->addSql('ALTER TABLE players_game ADD CONSTRAINT FK_B112FF9C7B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE players_game ADD CONSTRAINT FK_B112FF9CE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B112FF9C7B39D312 ON players_game (competition_id)');
        $this->addSql('CREATE INDEX IDX_B112FF9CE48FD905 ON players_game (game_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE players_game DROP CONSTRAINT FK_B112FF9C7B39D312');
        $this->addSql('ALTER TABLE players_game DROP CONSTRAINT FK_B112FF9CE48FD905');
        $this->addSql('DROP INDEX IDX_B112FF9C7B39D312');
        $this->addSql('DROP INDEX IDX_B112FF9CE48FD905');
        $this->addSql('ALTER TABLE players_game DROP competition_id');
        $this->addSql('ALTER TABLE players_game DROP game_id');
    }
}
