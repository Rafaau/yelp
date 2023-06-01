<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230601084111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE business ADD hours LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', ADD address VARCHAR(255) NOT NULL, ADD website VARCHAR(255) DEFAULT NULL, ADD phone_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE review ADD images LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', ADD stars INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE business DROP hours, DROP address, DROP website, DROP phone_number');
        $this->addSql('ALTER TABLE review DROP images, DROP stars');
    }
}
