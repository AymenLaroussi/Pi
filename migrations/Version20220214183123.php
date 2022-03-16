<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220214183123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, tournoi_id INT NOT NULL, label VARCHAR(255) NOT NULL, match_gagne INT DEFAULT NULL, vainqueur TINYINT(1) DEFAULT NULL, INDEX IDX_2449BA15F607770A (tournoi_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, nomeven VARCHAR(100) NOT NULL, lieuevent VARCHAR(150) NOT NULL, datevent DATE NOT NULL, heurevent TIME NOT NULL, datefin DATE NOT NULL, nbrplace INT NOT NULL, image VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, description VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, promo TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits_categorie (produits_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_215F5334CD11A2CF (produits_id), INDEX IDX_215F5334BCF5E72D (categorie_id), PRIMARY KEY(produits_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournoi (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, nbr_equipes INT NOT NULL, nbr_joueur_eq INT NOT NULL, prix DOUBLE PRECISION DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, discord_channel VARCHAR(255) NOT NULL, time DATETIME DEFAULT NULL, jeu VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA15F607770A FOREIGN KEY (tournoi_id) REFERENCES tournoi (id)');
        $this->addSql('ALTER TABLE produits_categorie ADD CONSTRAINT FK_215F5334CD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produits_categorie ADD CONSTRAINT FK_215F5334BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produits_categorie DROP FOREIGN KEY FK_215F5334BCF5E72D');
        $this->addSql('ALTER TABLE produits_categorie DROP FOREIGN KEY FK_215F5334CD11A2CF');
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA15F607770A');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE produits');
        $this->addSql('DROP TABLE produits_categorie');
        $this->addSql('DROP TABLE tournoi');
    }
}
