<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230317111129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tax (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, amount DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO tax (id, name, amount) VALUES (1, "5.5%", 0.055)');
        $this->addSql('ALTER TABLE order_item ADD tax DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE shop_product_variant ADD tax_id INT DEFAULT 1');
        $this->addSql('ALTER TABLE shop_product_variant ADD CONSTRAINT FK_C969A029B2A824D8 FOREIGN KEY (tax_id) REFERENCES tax (id)');
        $this->addSql('CREATE INDEX IDX_C969A029B2A824D8 ON shop_product_variant (tax_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_product_variant DROP FOREIGN KEY FK_C969A029B2A824D8');
        $this->addSql('DROP TABLE tax');
        $this->addSql('ALTER TABLE order_item DROP tax');
        $this->addSql('DROP INDEX IDX_C969A029B2A824D8 ON shop_product_variant');
        $this->addSql('ALTER TABLE shop_product_variant DROP tax_id');
    }
}
