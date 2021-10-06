<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201126233025 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE euromillions_tirages_euromillions_stats');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE euromillions_tirages_euromillions_stats (euromillions_tirages_id INT NOT NULL, euromillions_stats_id INT NOT NULL, INDEX IDX_D1DC20C2091E471 (euromillions_tirages_id), INDEX IDX_D1DC20C6152A3F (euromillions_stats_id), PRIMARY KEY(euromillions_tirages_id, euromillions_stats_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE euromillions_tirages_euromillions_stats ADD CONSTRAINT FK_D1DC20C2091E471 FOREIGN KEY (euromillions_tirages_id) REFERENCES euromillions_tirages (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE euromillions_tirages_euromillions_stats ADD CONSTRAINT FK_D1DC20C6152A3F FOREIGN KEY (euromillions_stats_id) REFERENCES euromillions_stats (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
