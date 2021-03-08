<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210308150457 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, text VARCHAR(900) NOT NULL, image VARCHAR(255) NOT NULL, date_ajout DATETIME NOT NULL, etat_ajout INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_article_cat (article_id INT NOT NULL, article_cat_id INT NOT NULL, INDEX IDX_96C20AB57294869C (article_id), INDEX IDX_96C20AB58C6A3EC3 (article_cat_id), PRIMARY KEY(article_id, article_cat_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_cat (id INT AUTO_INCREMENT NOT NULL, categorie VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_article_cat ADD CONSTRAINT FK_96C20AB57294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_article_cat ADD CONSTRAINT FK_96C20AB58C6A3EC3 FOREIGN KEY (article_cat_id) REFERENCES article_cat (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_article_cat DROP FOREIGN KEY FK_96C20AB57294869C');
        $this->addSql('ALTER TABLE article_article_cat DROP FOREIGN KEY FK_96C20AB58C6A3EC3');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_article_cat');
        $this->addSql('DROP TABLE article_cat');
    }
}
