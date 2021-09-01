<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210625161711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competition ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE competition ADD description VARCHAR(1000) DEFAULT NULL');
        $this->addSql('ALTER TABLE competition ADD address VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE competition ADD day TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE competition ADD time TIME(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE competition ADD code VARCHAR(1000) NOT NULL');
        $this->addSql('ALTER TABLE game ADD first_player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game ADD second_player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE game ADD ended_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE game ADD part_number INT NOT NULL');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C65EB6591 FOREIGN KEY (first_player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CA40D7457 FOREIGN KEY (second_player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_232B318C65EB6591 ON game (first_player_id)');
        $this->addSql('CREATE INDEX IDX_232B318CA40D7457 ON game (second_player_id)');
        $this->addSql('ALTER TABLE player ADD competition_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player ADD first_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE player ADD last_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE player ADD patronymic VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A657B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_98197A657B39D312 ON player (competition_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE competition DROP name');
        $this->addSql('ALTER TABLE competition DROP description');
        $this->addSql('ALTER TABLE competition DROP address');
        $this->addSql('ALTER TABLE competition DROP day');
        $this->addSql('ALTER TABLE competition DROP time');
        $this->addSql('ALTER TABLE competition DROP code');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318C65EB6591');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318CA40D7457');
        $this->addSql('DROP INDEX IDX_232B318C65EB6591');
        $this->addSql('DROP INDEX IDX_232B318CA40D7457');
        $this->addSql('ALTER TABLE game DROP first_player_id');
        $this->addSql('ALTER TABLE game DROP second_player_id');
        $this->addSql('ALTER TABLE game DROP created_at');
        $this->addSql('ALTER TABLE game DROP ended_at');
        $this->addSql('ALTER TABLE game DROP part_number');
        $this->addSql('ALTER TABLE player DROP CONSTRAINT FK_98197A657B39D312');
        $this->addSql('DROP INDEX IDX_98197A657B39D312');
        $this->addSql('ALTER TABLE player DROP competition_id');
        $this->addSql('ALTER TABLE player DROP first_name');
        $this->addSql('ALTER TABLE player DROP last_name');
        $this->addSql('ALTER TABLE player DROP patronymic');
    }
}
