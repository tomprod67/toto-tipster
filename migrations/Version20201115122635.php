<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201115122635 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE euromillions_tirages DROP FOREIGN KEY FK_5579199C9502F0B');
        $this->addSql('DROP INDEX IDX_5579199C9502F0B ON euromillions_tirages');
        $this->addSql('ALTER TABLE euromillions_tirages DROP stat_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE euromillions_tirages ADD stat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE euromillions_tirages ADD CONSTRAINT FK_5579199C9502F0B FOREIGN KEY (stat_id) REFERENCES euromillions_stats (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_5579199C9502F0B ON euromillions_tirages (stat_id)');
    }
}
