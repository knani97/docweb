<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210402071414 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, id_cat_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, text VARCHAR(900) NOT NULL, image VARCHAR(255) NOT NULL, date_ajout DATETIME NOT NULL, etat_ajout INT NOT NULL, id_user INT NOT NULL, INDEX IDX_23A0E66C09A1CAE (id_cat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_cat (id INT AUTO_INCREMENT NOT NULL, categorie VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE calendrier (id INT AUTO_INCREMENT NOT NULL, uid_id INT DEFAULT NULL, type INT NOT NULL, email TINYINT(1) NOT NULL, couleur VARCHAR(255) NOT NULL, timezone VARCHAR(255) NOT NULL, format INT NOT NULL, INDEX IDX_B2753CB9534B549B (uid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, prix DOUBLE PRECISION DEFAULT NULL, date_achat VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, user_id INT NOT NULL, body VARCHAR(1024) NOT NULL, created_at DATETIME NOT NULL, edited_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_9474526C4B89032C (post_id), INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaires (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, id_art_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, date_ajout DATETIME NOT NULL, INDEX IDX_D9BEC0C479F37AE5 (id_user_id), INDEX IDX_D9BEC0C4AA1250F7 (id_art_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cv (id INT AUTO_INCREMENT NOT NULL, specialite VARCHAR(255) NOT NULL, diplome VARCHAR(255) NOT NULL, file VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE disponibilite (id INT AUTO_INCREMENT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, duree_rdv TIME NOT NULL, duree_pause TIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche (id INT AUTO_INCREMENT NOT NULL, id_med_id INT DEFAULT NULL, nom_commerciale VARCHAR(255) DEFAULT NULL, dosage INT DEFAULT NULL, utilisation VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_4C13CC785F098C81 (id_med_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medicament (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, fournisseur VARCHAR(255) DEFAULT NULL, prix_achat DOUBLE PRECISION DEFAULT NULL, poid DOUBLE PRECISION DEFAULT NULL, fiche_exist TINYINT(1) DEFAULT NULL, img VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, somme DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pharmacie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, adr VARCHAR(255) DEFAULT NULL, gouv VARCHAR(255) DEFAULT NULL, img_pat VARCHAR(255) DEFAULT NULL, note INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, body VARCHAR(1024) NOT NULL, created_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, edited_at DATETIME DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, INDEX IDX_5A8A6C8DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rdv (id INT AUTO_INCREMENT NOT NULL, tache_dispo_id INT NOT NULL, patient_id INT DEFAULT NULL, medecin_id INT NOT NULL, tache_user_id INT DEFAULT NULL, date DATETIME NOT NULL, etat INT NOT NULL, description VARCHAR(255) DEFAULT NULL, jointure VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_10C31F861A4D0DF4 (tache_dispo_id), INDEX IDX_10C31F866B899279 (patient_id), INDEX IDX_10C31F864F31A84 (medecin_id), UNIQUE INDEX UNIQ_10C31F866D38ED20 (tache_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reagit (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_art_id INT DEFAULT NULL, type_react INT NOT NULL, INDEX IDX_8BAFDD5C79F37AE5 (id_user_id), INDEX IDX_8BAFDD5CAA1250F7 (id_art_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tache (id INT AUTO_INCREMENT NOT NULL, calendrier INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, type VARCHAR(20) NOT NULL, couleur VARCHAR(20) NOT NULL, date DATETIME NOT NULL, duree TIME NOT NULL, INDEX IDX_93872075B2753CB9 (calendrier), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, cv_id INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, type INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, activation_token VARCHAR(50) DEFAULT NULL, reset_token VARCHAR(50) DEFAULT NULL, articles VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649CFE419E2 (cv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, panier_id INT DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, source VARCHAR(255) DEFAULT NULL, paye TINYINT(1) DEFAULT NULL, prix DOUBLE PRECISION DEFAULT NULL, note INT DEFAULT NULL, INDEX IDX_7CC7DA2CF77D927C (panier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video_commande (video_id INT NOT NULL, commande_id INT NOT NULL, INDEX IDX_C66894C129C1004E (video_id), INDEX IDX_C66894C182EA2E54 (commande_id), PRIMARY KEY(video_id, commande_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66C09A1CAE FOREIGN KEY (id_cat_id) REFERENCES article_cat (id)');
        $this->addSql('ALTER TABLE calendrier ADD CONSTRAINT FK_B2753CB9534B549B FOREIGN KEY (uid_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C479F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4AA1250F7 FOREIGN KEY (id_art_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE fiche ADD CONSTRAINT FK_4C13CC785F098C81 FOREIGN KEY (id_med_id) REFERENCES medicament (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_10C31F861A4D0DF4 FOREIGN KEY (tache_dispo_id) REFERENCES tache (id)');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_10C31F866B899279 FOREIGN KEY (patient_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_10C31F864F31A84 FOREIGN KEY (medecin_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_10C31F866D38ED20 FOREIGN KEY (tache_user_id) REFERENCES tache (id)');
        $this->addSql('ALTER TABLE reagit ADD CONSTRAINT FK_8BAFDD5C79F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reagit ADD CONSTRAINT FK_8BAFDD5CAA1250F7 FOREIGN KEY (id_art_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_93872075B2753CB9 FOREIGN KEY (calendrier) REFERENCES calendrier (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id)');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2CF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('ALTER TABLE video_commande ADD CONSTRAINT FK_C66894C129C1004E FOREIGN KEY (video_id) REFERENCES video (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video_commande ADD CONSTRAINT FK_C66894C182EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4AA1250F7');
        $this->addSql('ALTER TABLE reagit DROP FOREIGN KEY FK_8BAFDD5CAA1250F7');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66C09A1CAE');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_93872075B2753CB9');
        $this->addSql('ALTER TABLE video_commande DROP FOREIGN KEY FK_C66894C182EA2E54');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649CFE419E2');
        $this->addSql('ALTER TABLE fiche DROP FOREIGN KEY FK_4C13CC785F098C81');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2CF77D927C');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE rdv DROP FOREIGN KEY FK_10C31F861A4D0DF4');
        $this->addSql('ALTER TABLE rdv DROP FOREIGN KEY FK_10C31F866D38ED20');
        $this->addSql('ALTER TABLE calendrier DROP FOREIGN KEY FK_B2753CB9534B549B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C479F37AE5');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE rdv DROP FOREIGN KEY FK_10C31F866B899279');
        $this->addSql('ALTER TABLE rdv DROP FOREIGN KEY FK_10C31F864F31A84');
        $this->addSql('ALTER TABLE reagit DROP FOREIGN KEY FK_8BAFDD5C79F37AE5');
        $this->addSql('ALTER TABLE video_commande DROP FOREIGN KEY FK_C66894C129C1004E');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_cat');
        $this->addSql('DROP TABLE calendrier');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE commentaires');
        $this->addSql('DROP TABLE cv');
        $this->addSql('DROP TABLE disponibilite');
        $this->addSql('DROP TABLE fiche');
        $this->addSql('DROP TABLE medicament');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE pharmacie');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE rdv');
        $this->addSql('DROP TABLE reagit');
        $this->addSql('DROP TABLE tache');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE video');
        $this->addSql('DROP TABLE video_commande');
    }
}
