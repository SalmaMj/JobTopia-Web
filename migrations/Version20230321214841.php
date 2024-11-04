<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230321214841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidatures DROP FOREIGN KEY candidatures_ibfk_3');
        $this->addSql('ALTER TABLE candidatures DROP FOREIGN KEY candidatures_ibfk_2');
        $this->addSql('ALTER TABLE conventions DROP FOREIGN KEY conventions_ibfk_1');
        $this->addSql('ALTER TABLE conventions DROP FOREIGN KEY conventions_ibfk_3');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY messages_ibfk_1');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY messages_ibfk_2');
        $this->addSql('ALTER TABLE offres DROP FOREIGN KEY offres_ibfk_2');
        $this->addSql('ALTER TABLE reclamations DROP FOREIGN KEY reclamations_ibfk_2');
        $this->addSql('DROP TABLE candidatures');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE conventions');
        $this->addSql('DROP TABLE messages');
        $this->addSql('DROP TABLE offres');
        $this->addSql('DROP TABLE reclamations');
        $this->addSql('DROP TABLE users');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidatures (id INT AUTO_INCREMENT NOT NULL, offreID INT NOT NULL, freelancerID INT NOT NULL, isDeleted TINYINT(1) DEFAULT 0 NOT NULL, lettreMotivation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, etat VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, cv VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, score VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX freelancerID (freelancerID), INDEX offreID (offreID), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE conventions (id INT AUTO_INCREMENT NOT NULL, clientID INT NOT NULL, freelancerID INT NOT NULL, project_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, start_date DATE NOT NULL, end_date DATE NOT NULL, etat VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, payment_amount NUMERIC(10, 0) NOT NULL, isdeleted TINYINT(1) DEFAULT 0 NOT NULL, INDEX clientID (clientID), INDEX freelancerID (freelancerID), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, receiverID INT NOT NULL, senderID INT NOT NULL, contenu VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_heure DATETIME NOT NULL, INDEX receiverID (receiverID), INDEX senderID (senderID), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE offres (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, clientID INT NOT NULL, dl DATE NOT NULL, dc DATE NOT NULL, categorie VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, etat VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, role VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, titre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, skill1 VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, skill2 VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, skill3 VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX clientID (clientID), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reclamations (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, userID INT NOT NULL, titre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, etat VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date DATE DEFAULT \'current_timestamp()\' NOT NULL, IsDeleted TINYINT(1) NOT NULL, INDEX userID (userID), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prenom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, telephone VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, imagePath VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, categorie VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, salaire DOUBLE PRECISION DEFAULT \'NULL\', cv VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, note DOUBLE PRECISION DEFAULT \'NULL\', nbAvis INT DEFAULT 0 NOT NULL, role VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, profession VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, IsDeleted TINYINT(1) NOT NULL, Code VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, Confirmed TINYINT(1) NOT NULL, codePassword VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE candidatures ADD CONSTRAINT candidatures_ibfk_3 FOREIGN KEY (freelancerID) REFERENCES users (id)');
        $this->addSql('ALTER TABLE candidatures ADD CONSTRAINT candidatures_ibfk_2 FOREIGN KEY (offreID) REFERENCES offres (id)');
        $this->addSql('ALTER TABLE conventions ADD CONSTRAINT conventions_ibfk_1 FOREIGN KEY (clientID) REFERENCES users (id)');
        $this->addSql('ALTER TABLE conventions ADD CONSTRAINT conventions_ibfk_3 FOREIGN KEY (freelancerID) REFERENCES users (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT messages_ibfk_1 FOREIGN KEY (receiverID) REFERENCES users (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT messages_ibfk_2 FOREIGN KEY (senderID) REFERENCES users (id)');
        $this->addSql('ALTER TABLE offres ADD CONSTRAINT offres_ibfk_2 FOREIGN KEY (clientID) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reclamations ADD CONSTRAINT reclamations_ibfk_2 FOREIGN KEY (userID) REFERENCES users (id)');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
