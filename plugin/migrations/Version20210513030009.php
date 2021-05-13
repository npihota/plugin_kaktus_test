<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210513030009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE articles_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE authors_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE articles (id INT NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE article_author (article_id INT NOT NULL, author_id INT NOT NULL, PRIMARY KEY(article_id, author_id))');
        $this->addSql('CREATE INDEX IDX_D7684F487294869C ON article_author (article_id)');
        $this->addSql('CREATE INDEX IDX_D7684F48F675F31B ON article_author (author_id)');
        $this->addSql('CREATE TABLE authors (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8E0C2A515E237E06 ON authors (name)');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9F85E0677 ON users (username)');
        $this->addSql('ALTER TABLE article_author ADD CONSTRAINT FK_D7684F487294869C FOREIGN KEY (article_id) REFERENCES articles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE article_author ADD CONSTRAINT FK_D7684F48F675F31B FOREIGN KEY (author_id) REFERENCES authors (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_author DROP CONSTRAINT FK_D7684F487294869C');
        $this->addSql('ALTER TABLE article_author DROP CONSTRAINT FK_D7684F48F675F31B');
        $this->addSql('DROP SEQUENCE articles_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE authors_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE article_author');
        $this->addSql('DROP TABLE authors');
        $this->addSql('DROP TABLE users');
    }
}
