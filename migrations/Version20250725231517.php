<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250725231517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE empresa (id SERIAL NOT NULL, nome VARCHAR(255) NOT NULL, cnpj VARCHAR(14) NOT NULL, data_fundacao DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE socio (id SERIAL NOT NULL, empresa_id INT NOT NULL, nome VARCHAR(255) NOT NULL, cpf VARCHAR(11) NOT NULL, percentual_participacao DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_38B65309521E1991 ON socio (empresa_id)');
        $this->addSql('ALTER TABLE socio ADD CONSTRAINT FK_38B65309521E1991 FOREIGN KEY (empresa_id) REFERENCES empresa (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE socio DROP CONSTRAINT FK_38B65309521E1991');
        $this->addSql('DROP TABLE empresa');
        $this->addSql('DROP TABLE socio');
    }
}
