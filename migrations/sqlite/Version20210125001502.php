<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210125001502 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE follow (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, follower_id INTEGER NOT NULL, followed_id INTEGER NOT NULL, created_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_68344470AC24F853 ON follow (follower_id)');
        $this->addSql('CREATE INDEX IDX_68344470D956F010 ON follow (followed_id)');
        $this->addSql('CREATE TABLE tweet (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER DEFAULT NULL, retweeting_id INTEGER DEFAULT NULL, content CLOB NOT NULL, created_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_3D660A3BF675F31B ON tweet (author_id)');
        $this->addSql('CREATE INDEX IDX_3D660A3B9F93F1EF ON tweet (retweeting_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, avatar VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, confirmation_token VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE follow');
        $this->addSql('DROP TABLE tweet');
        $this->addSql('DROP TABLE user');
    }
}
