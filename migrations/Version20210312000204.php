<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210312000204 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reagit (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_art_id INT DEFAULT NULL, type_react INT NOT NULL, INDEX IDX_8BAFDD5C79F37AE5 (id_user_id), INDEX IDX_8BAFDD5CAA1250F7 (id_art_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reagit ADD CONSTRAINT FK_8BAFDD5C79F37AE5 FOREIGN KEY (id_user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reagit ADD CONSTRAINT FK_8BAFDD5CAA1250F7 FOREIGN KEY (id_art_id) REFERENCES article (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reagit');
    }
}
