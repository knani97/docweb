<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210312141008 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE calendrier (id INT AUTO_INCREMENT NOT NULL, uid_id INT DEFAULT NULL, type INT NOT NULL, email TINYINT(1) NOT NULL, couleur VARCHAR(255) NOT NULL, timezone VARCHAR(255) NOT NULL, format INT NOT NULL, INDEX IDX_B2753CB9534B549B (uid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE disponibilite (id INT AUTO_INCREMENT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, duree_rdv TIME NOT NULL, duree_pause TIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rdv (id INT AUTO_INCREMENT NOT NULL, tache_dispo_id INT NOT NULL, patient_id INT DEFAULT NULL, medecin_id INT NOT NULL, tache_user_id INT DEFAULT NULL, date DATETIME NOT NULL, etat INT NOT NULL, description VARCHAR(255) DEFAULT NULL, jointure VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_10C31F861A4D0DF4 (tache_dispo_id), INDEX IDX_10C31F866B899279 (patient_id), INDEX IDX_10C31F864F31A84 (medecin_id), UNIQUE INDEX UNIQ_10C31F866D38ED20 (tache_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tache (id INT AUTO_INCREMENT NOT NULL, calendrier INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, type VARCHAR(20) NOT NULL, couleur VARCHAR(20) NOT NULL, date DATETIME NOT NULL, duree TIME NOT NULL, INDEX IDX_93872075B2753CB9 (calendrier), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, worker_id INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, type INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D6496B20BA36 (worker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_worker (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE calendrier ADD CONSTRAINT FK_B2753CB9534B549B FOREIGN KEY (uid_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_10C31F861A4D0DF4 FOREIGN KEY (tache_dispo_id) REFERENCES tache (id)');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_10C31F866B899279 FOREIGN KEY (patient_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_10C31F864F31A84 FOREIGN KEY (medecin_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_10C31F866D38ED20 FOREIGN KEY (tache_user_id) REFERENCES tache (id)');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_93872075B2753CB9 FOREIGN KEY (calendrier) REFERENCES calendrier (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6496B20BA36 FOREIGN KEY (worker_id) REFERENCES user_worker (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_93872075B2753CB9');
        $this->addSql('ALTER TABLE rdv DROP FOREIGN KEY FK_10C31F861A4D0DF4');
        $this->addSql('ALTER TABLE rdv DROP FOREIGN KEY FK_10C31F866D38ED20');
        $this->addSql('ALTER TABLE calendrier DROP FOREIGN KEY FK_B2753CB9534B549B');
        $this->addSql('ALTER TABLE rdv DROP FOREIGN KEY FK_10C31F866B899279');
        $this->addSql('ALTER TABLE rdv DROP FOREIGN KEY FK_10C31F864F31A84');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6496B20BA36');
        $this->addSql('DROP TABLE calendrier');
        $this->addSql('DROP TABLE disponibilite');
        $this->addSql('DROP TABLE rdv');
        $this->addSql('DROP TABLE tache');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_worker');
    }
}
