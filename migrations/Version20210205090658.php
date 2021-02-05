<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210205090658 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partido DROP FOREIGN KEY FK_4E79750B88774E73');
        $this->addSql('ALTER TABLE partido DROP FOREIGN KEY FK_4E79750B8C243011');
        $this->addSql('ALTER TABLE partido ADD disputado TINYINT(1) DEFAULT NULL, ADD fecha_asignacion DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750B88774E73 FOREIGN KEY (equipo_local_id) REFERENCES equipo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750B8C243011 FOREIGN KEY (equipo_visitante_id) REFERENCES equipo (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partido DROP FOREIGN KEY FK_4E79750B88774E73');
        $this->addSql('ALTER TABLE partido DROP FOREIGN KEY FK_4E79750B8C243011');
        $this->addSql('ALTER TABLE partido DROP disputado, DROP fecha_asignacion');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750B88774E73 FOREIGN KEY (equipo_local_id) REFERENCES equipo (id)');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750B8C243011 FOREIGN KEY (equipo_visitante_id) REFERENCES equipo (id)');
    }
}
