<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201126231300 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE euromillions_stats ADD tirages_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE euromillions_stats ADD CONSTRAINT FK_A5E36DD68BF57F58 FOREIGN KEY (tirages_id) REFERENCES euromillions_tirages (id)');
        $this->addSql('CREATE INDEX IDX_A5E36DD68BF57F58 ON euromillions_stats (tirages_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE euromillions_stats DROP FOREIGN KEY FK_A5E36DD68BF57F58');
        $this->addSql('DROP INDEX IDX_A5E36DD68BF57F58 ON euromillions_stats');
        $this->addSql('ALTER TABLE euromillions_stats DROP tirages_id');
    }
}
