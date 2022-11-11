<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221111045019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_like (article_id INT NOT NULL, like_id INT NOT NULL, INDEX IDX_1C21C7B27294869C (article_id), INDEX IDX_1C21C7B2859BFA32 (like_id), PRIMARY KEY(article_id, like_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_like ADD CONSTRAINT FK_1C21C7B27294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_like ADD CONSTRAINT FK_1C21C7B2859BFA32 FOREIGN KEY (like_id) REFERENCES `like` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B37294869C');
        $this->addSql('DROP INDEX IDX_AC6340B37294869C ON `like`');
        $this->addSql('ALTER TABLE `like` DROP article_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_like DROP FOREIGN KEY FK_1C21C7B27294869C');
        $this->addSql('ALTER TABLE article_like DROP FOREIGN KEY FK_1C21C7B2859BFA32');
        $this->addSql('DROP TABLE article_like');
        $this->addSql('ALTER TABLE `like` ADD article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B37294869C FOREIGN KEY (article_id) REFERENCES article (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_AC6340B37294869C ON `like` (article_id)');
    }
}
