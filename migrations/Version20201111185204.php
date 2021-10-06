<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201111185204 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE euromillions_stats (id INT AUTO_INCREMENT NOT NULL, year VARCHAR(4) NOT NULL, nb_tirages INT DEFAULT NULL, nums LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', occurence LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE euromillions_tirages (id INT AUTO_INCREMENT NOT NULL, stat_id INT DEFAULT NULL, num1 INT NOT NULL, num2 INT NOT NULL, num3 INT NOT NULL, num4 INT NOT NULL, num5 INT NOT NULL, num_c1 INT NOT NULL, num_c2 INT NOT NULL, date DATE DEFAULT NULL, year INT NOT NULL, INDEX IDX_5579199C9502F0B (stat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE euromillions_tirages ADD CONSTRAINT FK_5579199C9502F0B FOREIGN KEY (stat_id) REFERENCES euromillions_stats (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE euromillions_tirages DROP FOREIGN KEY FK_5579199C9502F0B');
        $this->addSql('DROP TABLE euromillions_stats');
        $this->addSql('DROP TABLE euromillions_tirages');
    }
}
