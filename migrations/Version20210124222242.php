<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210124222242 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tweet ADD retweeting_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tweet ADD CONSTRAINT FK_3D660A3B9F93F1EF FOREIGN KEY (retweeting_id) REFERENCES tweet (id)');
        $this->addSql('CREATE INDEX IDX_3D660A3B9F93F1EF ON tweet (retweeting_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tweet DROP FOREIGN KEY FK_3D660A3B9F93F1EF');
        $this->addSql('DROP INDEX IDX_3D660A3B9F93F1EF ON tweet');
        $this->addSql('ALTER TABLE tweet DROP retweeting_id');
    }
}
