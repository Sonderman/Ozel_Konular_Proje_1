<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200114094143 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, subject VARCHAR(100) DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, status VARCHAR(20) DEFAULT NULL, ip VARCHAR(30) DEFAULT NULL, userid INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, rate DOUBLE PRECISION DEFAULT NULL, carid INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, subject VARCHAR(100) DEFAULT NULL, message VARCHAR(255) DEFAULT NULL, status VARCHAR(10) DEFAULT NULL, ip VARCHAR(20) DEFAULT NULL, note VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, title VARCHAR(100) DEFAULT NULL, keywords VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, status VARCHAR(30) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, contract_id INT DEFAULT NULL, rate DOUBLE PRECISION DEFAULT NULL, seats INT DEFAULT NULL, doors INT DEFAULT NULL, has_airconditions TINYINT(1) DEFAULT NULL, gearbox VARCHAR(15) DEFAULT NULL, transmission INT DEFAULT NULL, fuel_type VARCHAR(20) DEFAULT NULL, baggage_capacity VARCHAR(100) DEFAULT NULL, brand VARCHAR(50) DEFAULT NULL, model VARCHAR(100) DEFAULT NULL, year INT DEFAULT NULL, price_for_a_day INT DEFAULT NULL, owner_id INT DEFAULT NULL, detail LONGTEXT DEFAULT NULL, image VARCHAR(50) DEFAULT NULL, INDEX IDX_773DE69D12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, parentid INT DEFAULT NULL, title VARCHAR(150) DEFAULT NULL, keywords VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, image VARCHAR(100) DEFAULT NULL, status VARCHAR(10) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contract (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, car_id INT DEFAULT NULL, pick_up_date VARCHAR(255) DEFAULT NULL, drop_off_date VARCHAR(255) DEFAULT NULL, pick_up_location VARCHAR(255) DEFAULT NULL, drop_off_location VARCHAR(255) DEFAULT NULL, status VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE faq (id INT AUTO_INCREMENT NOT NULL, question VARCHAR(255) NOT NULL, answer VARCHAR(255) DEFAULT NULL, status VARCHAR(15) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, car_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, image VARCHAR(150) NOT NULL, INDEX IDX_C53D045FC3C6F69F (car_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE settings (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(150) DEFAULT NULL, keywords VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, company VARCHAR(100) NOT NULL, address VARCHAR(255) NOT NULL, phone INT NOT NULL, fax INT NOT NULL, email VARCHAR(100) NOT NULL, smtpserver VARCHAR(255) DEFAULT NULL, smtpemail VARCHAR(255) DEFAULT NULL, smtppassword VARCHAR(255) DEFAULT NULL, smtpport VARCHAR(255) DEFAULT NULL, facebook VARCHAR(255) DEFAULT NULL, instagram VARCHAR(255) DEFAULT NULL, twitter VARCHAR(255) DEFAULT NULL, aboutus VARCHAR(255) DEFAULT NULL, status VARCHAR(15) NOT NULL, contact LONGTEXT DEFAULT NULL, reference LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(20) DEFAULT NULL, surname VARCHAR(20) DEFAULT NULL, image VARCHAR(50) DEFAULT NULL, status VARCHAR(10) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FC3C6F69F');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D12469DE2');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE messages');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE contract');
        $this->addSql('DROP TABLE faq');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE settings');
        $this->addSql('DROP TABLE user');
    }
}
