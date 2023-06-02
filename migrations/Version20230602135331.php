<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230602135331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE review ADD reactions LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE user ADD description VARCHAR(255) DEFAULT NULL, ADD things_ilove VARCHAR(255) DEFAULT NULL, ADD my_hometown VARCHAR(255) DEFAULT NULL, ADD when_iam_not_whelping VARCHAR(255) DEFAULT NULL, ADD why_you_should_read VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE review DROP reactions');
        $this->addSql('ALTER TABLE user DROP description, DROP things_ilove, DROP my_hometown, DROP when_iam_not_whelping, DROP why_you_should_read');
    }
}
