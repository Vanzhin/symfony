<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221111043719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `like` ADD article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B37294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_AC6340B37294869C ON `like` (article_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B37294869C');
        $this->addSql('DROP INDEX IDX_AC6340B37294869C ON `like`');
        $this->addSql('ALTER TABLE `like` DROP article_id');
    }
}
