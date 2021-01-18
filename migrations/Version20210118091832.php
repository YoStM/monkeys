<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210118091832 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE admin_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE project_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_profile_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE admin (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_880E0D76F85E0677 ON admin (username)');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, label VARCHAR(55) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE project (id INT NOT NULL, category_id_id INT NOT NULL, user_id_id INT NOT NULL, title VARCHAR(155) NOT NULL, description TEXT NOT NULL, create_date DATE NOT NULL, modify_date DATE DEFAULT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE9777D11E ON project (category_id_id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE9D86650F ON project (user_id_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE TABLE user_profile (id INT NOT NULL, user_id_id INT NOT NULL, first_name VARCHAR(55) NOT NULL, last_name VARCHAR(55) NOT NULL, company_name VARCHAR(255) NOT NULL, siret BIGINT NOT NULL, email VARCHAR(55) NOT NULL, activity VARCHAR(255) NOT NULL, about_user TEXT DEFAULT NULL, credit INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D95AB4059D86650F ON user_profile (user_id_id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE9777D11E FOREIGN KEY (category_id_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE9D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB4059D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EE9777D11E');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EE9D86650F');
        $this->addSql('ALTER TABLE user_profile DROP CONSTRAINT FK_D95AB4059D86650F');
        $this->addSql('DROP SEQUENCE admin_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE project_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE user_profile_id_seq CASCADE');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_profile');
    }
}
