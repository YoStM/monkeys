<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210121115611 extends AbstractMigration
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
        $this->addSql('CREATE SEQUENCE conversation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE offer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE project_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE project_contributor_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE project_owner_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_profile_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE admin (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_880E0D76F85E0677 ON admin (username)');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, label VARCHAR(55) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE conversation (id INT NOT NULL, owner_id_id INT NOT NULL, contributor_id_id INT NOT NULL, is_initiated BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8A8E26E98FDDAB70 ON conversation (owner_id_id)');
        $this->addSql('CREATE INDEX IDX_8A8E26E9EEF3BA95 ON conversation (contributor_id_id)');
        $this->addSql('CREATE TABLE message (id INT NOT NULL, conversation_id_id INT NOT NULL, content TEXT NOT NULL, create_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6BD307F6B92BD7B ON message (conversation_id_id)');
        $this->addSql('CREATE TABLE offer (id INT NOT NULL, project_id INT NOT NULL, contributor_id_id INT NOT NULL, description TEXT NOT NULL, deadline DATE NOT NULL, price INT NOT NULL, create_date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_29D6873E166D1F9C ON offer (project_id)');
        $this->addSql('CREATE INDEX IDX_29D6873EEEF3BA95 ON offer (contributor_id_id)');
        $this->addSql('CREATE TABLE project (id INT NOT NULL, category_id_id INT NOT NULL, owner_id_id INT NOT NULL, title VARCHAR(155) NOT NULL, description TEXT NOT NULL, create_date DATE NOT NULL, modify_date DATE DEFAULT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE9777D11E ON project (category_id_id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE8FDDAB70 ON project (owner_id_id)');
        $this->addSql('CREATE TABLE project_contributor (id INT NOT NULL, user_id_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_12A060689D86650F ON project_contributor (user_id_id)');
        $this->addSql('CREATE TABLE project_owner (id INT NOT NULL, user_id_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DC35EFE29D86650F ON project_owner (user_id_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE TABLE user_profile (id INT NOT NULL, user_id_id INT NOT NULL, first_name VARCHAR(55) NOT NULL, last_name VARCHAR(55) NOT NULL, company_name VARCHAR(255) NOT NULL, siret BIGINT NOT NULL, email VARCHAR(55) NOT NULL, activity VARCHAR(255) NOT NULL, about_user TEXT DEFAULT NULL, credit INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D95AB4059D86650F ON user_profile (user_id_id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E98FDDAB70 FOREIGN KEY (owner_id_id) REFERENCES project_owner (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9EEF3BA95 FOREIGN KEY (contributor_id_id) REFERENCES project_contributor (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F6B92BD7B FOREIGN KEY (conversation_id_id) REFERENCES conversation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EEEF3BA95 FOREIGN KEY (contributor_id_id) REFERENCES project_contributor (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE9777D11E FOREIGN KEY (category_id_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE8FDDAB70 FOREIGN KEY (owner_id_id) REFERENCES project_owner (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_contributor ADD CONSTRAINT FK_12A060689D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_owner ADD CONSTRAINT FK_DC35EFE29D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB4059D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EE9777D11E');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307F6B92BD7B');
        $this->addSql('ALTER TABLE offer DROP CONSTRAINT FK_29D6873E166D1F9C');
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT FK_8A8E26E9EEF3BA95');
        $this->addSql('ALTER TABLE offer DROP CONSTRAINT FK_29D6873EEEF3BA95');
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT FK_8A8E26E98FDDAB70');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EE8FDDAB70');
        $this->addSql('ALTER TABLE project_contributor DROP CONSTRAINT FK_12A060689D86650F');
        $this->addSql('ALTER TABLE project_owner DROP CONSTRAINT FK_DC35EFE29D86650F');
        $this->addSql('ALTER TABLE user_profile DROP CONSTRAINT FK_D95AB4059D86650F');
        $this->addSql('DROP SEQUENCE admin_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE conversation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE message_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE offer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE project_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE project_contributor_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE project_owner_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE user_profile_id_seq CASCADE');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_contributor');
        $this->addSql('DROP TABLE project_owner');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_profile');
    }
}
