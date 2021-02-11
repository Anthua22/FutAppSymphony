<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210210150101 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat ADD emisor_id INT DEFAULT NULL, ADD receptor_id INT DEFAULT NULL, DROP emisor, DROP receptor');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA6BDF87DF FOREIGN KEY (emisor_id) REFERENCES usuario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA386D8D01 FOREIGN KEY (receptor_id) REFERENCES usuario (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_659DF2AA6BDF87DF ON chat (emisor_id)');
        $this->addSql('CREATE INDEX IDX_659DF2AA386D8D01 ON chat (receptor_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA6BDF87DF');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA386D8D01');
        $this->addSql('DROP INDEX IDX_659DF2AA6BDF87DF ON chat');
        $this->addSql('DROP INDEX IDX_659DF2AA386D8D01 ON chat');
        $this->addSql('ALTER TABLE chat ADD emisor INT NOT NULL, ADD receptor INT NOT NULL, DROP emisor_id, DROP receptor_id');
    }
}
