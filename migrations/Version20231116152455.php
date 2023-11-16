<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231116152455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE review_nrating (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, movie_id INT DEFAULT NULL, rating INT NOT NULL, review VARCHAR(1000) DEFAULT NULL, INDEX IDX_B440DBB0A76ED395 (user_id), INDEX IDX_B440DBB08F93B6FC (movie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE review_nrating ADD CONSTRAINT FK_B440DBB0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE review_nrating ADD CONSTRAINT FK_B440DBB08F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE review_nrating DROP FOREIGN KEY FK_B440DBB0A76ED395');
        $this->addSql('ALTER TABLE review_nrating DROP FOREIGN KEY FK_B440DBB08F93B6FC');
        $this->addSql('DROP TABLE review_nrating');
    }
}
