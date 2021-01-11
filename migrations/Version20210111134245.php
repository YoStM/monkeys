<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210111134245 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE projects_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, label VARCHAR(55) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE projects (id INT NOT NULL, category_id_id INT NOT NULL, user_id_id INT NOT NULL, title VARCHAR(155) NOT NULL, description TEXT NOT NULL, create_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, modify_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5C93B3A49777D11E ON projects (category_id_id)');
        $this->addSql('CREATE INDEX IDX_5C93B3A49D86650F ON projects (user_id_id)');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A49777D11E FOREIGN KEY (category_id_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A49D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE projects DROP CONSTRAINT FK_5C93B3A49777D11E');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE projects_id_seq CASCADE');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE projects');
    }
}
