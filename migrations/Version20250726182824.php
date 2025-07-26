<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250726182824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        // $this->addSql('CREATE TABLE "quadro_user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        // $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "quadro_user" (email)');
        // $this->addSql('DROP TABLE "user"');
        $this->addSql('ALTER TABLE "user" RENAME TO quadro_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // $this->addSql('CREATE SCHEMA public');
        // $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        // $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        // $this->addSql('CREATE UNIQUE INDEX uniq_identifier_email ON "user" (email)');
        // $this->addSql('DROP TABLE "quadro_user"');
        $this->addSql('ALTER TABLE quadro_user RENAME TO "user"');
    }
}
