<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170913073751 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE test_material (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test_color (id INT AUTO_INCREMENT NOT NULL, material_id INT DEFAULT NULL, r VARCHAR(255) NOT NULL, g VARCHAR(255) NOT NULL, b VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, INDEX IDX_6D88EEE2E308AC6F (material_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test_car_inspection (id INT AUTO_INCREMENT NOT NULL, car_id INT NOT NULL, date DATE NOT NULL, comment LONGTEXT NOT NULL, INDEX IDX_B4624299C3C6F69F (car_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test_car (id INT AUTO_INCREMENT NOT NULL, engine_id INT DEFAULT NULL, rescue_engine_id INT DEFAULT NULL, color_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, discr VARCHAR(255) NOT NULL, INDEX IDX_E403A5AE78C9C0A (engine_id), INDEX IDX_E403A5A34039FB6 (rescue_engine_id), INDEX IDX_E403A5A7ADA1FB5 (color_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test_car_engine (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, power INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE test_color ADD CONSTRAINT FK_6D88EEE2E308AC6F FOREIGN KEY (material_id) REFERENCES test_material (id)');
        $this->addSql('ALTER TABLE test_car_inspection ADD CONSTRAINT FK_B4624299C3C6F69F FOREIGN KEY (car_id) REFERENCES test_car (id)');
        $this->addSql('ALTER TABLE test_car ADD CONSTRAINT FK_E403A5AE78C9C0A FOREIGN KEY (engine_id) REFERENCES test_car_engine (id)');
        $this->addSql('ALTER TABLE test_car ADD CONSTRAINT FK_E403A5A34039FB6 FOREIGN KEY (rescue_engine_id) REFERENCES test_car_engine (id)');
        $this->addSql('ALTER TABLE test_car ADD CONSTRAINT FK_E403A5A7ADA1FB5 FOREIGN KEY (color_id) REFERENCES test_color (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE test_color DROP FOREIGN KEY FK_6D88EEE2E308AC6F');
        $this->addSql('ALTER TABLE test_car DROP FOREIGN KEY FK_E403A5A7ADA1FB5');
        $this->addSql('ALTER TABLE test_car_inspection DROP FOREIGN KEY FK_B4624299C3C6F69F');
        $this->addSql('ALTER TABLE test_car DROP FOREIGN KEY FK_E403A5AE78C9C0A');
        $this->addSql('ALTER TABLE test_car DROP FOREIGN KEY FK_E403A5A34039FB6');
        $this->addSql('DROP TABLE test_material');
        $this->addSql('DROP TABLE test_color');
        $this->addSql('DROP TABLE test_car_inspection');
        $this->addSql('DROP TABLE test_car');
        $this->addSql('DROP TABLE test_car_engine');
    }
}
