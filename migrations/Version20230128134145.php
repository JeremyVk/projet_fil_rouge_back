<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230128134145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_variant DROP FOREIGN KEY FK_6263973B727ACA70');
        $this->addSql('ALTER TABLE order_book_variant DROP FOREIGN KEY FK_66A30351A4C4E31C');
        $this->addSql('DROP INDEX IDX_6263973B727ACA70 ON book_variant');
        $this->addSql('ALTER TABLE book DROP title, DROP resume, DROP image, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE book_variant DROP parent_id, DROP stock, DROP unit_price, CHANGE id id INT NOT NULL');
        $this->addSql('CREATE TABLE shop_product (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, resume VARCHAR(500) NOT NULL, image VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_product_variant (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, stock INT NOT NULL, unit_price DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_C969A029727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book_variant ADD CONSTRAINT FK_6263973BBF396750 FOREIGN KEY (id) REFERENCES shop_product_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F093B69A9AF FOREIGN KEY (variant_id) REFERENCES shop_product_variant (id)');
        $this->addSql('ALTER TABLE shop_product_variant ADD CONSTRAINT FK_C969A029727ACA70 FOREIGN KEY (parent_id) REFERENCES shop_product (id)');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331BF396750 FOREIGN KEY (id) REFERENCES shop_product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331BF396750');
        $this->addSql('ALTER TABLE book_variant DROP FOREIGN KEY FK_6263973BBF396750');
        $this->addSql('ALTER TABLE order_book_variant ADD CONSTRAINT FK_66A30351A4C4E31C FOREIGN KEY (book_variant_id) REFERENCES book_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F093B69A9AF');
        $this->addSql('ALTER TABLE shop_product_variant DROP FOREIGN KEY FK_C969A029727ACA70');
        $this->addSql('DROP TABLE shop_product');
        $this->addSql('DROP TABLE shop_product_variant');
        $this->addSql('ALTER TABLE book_variant ADD stock INT NOT NULL, ADD unit_price DOUBLE PRECISION NOT NULL, ADD parent_id INT DEFAULT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE book_variant ADD parent_id INT DEFAULT NULL, ADD stock INT NOT NULL, ADD unit_price DOUBLE PRECISION NOT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE book_variant ADD CONSTRAINT FK_6263973B727ACA70 FOREIGN KEY (parent_id) REFERENCES book (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6263973B727ACA70 ON book_variant (parent_id)');
        $this->addSql('ALTER TABLE book ADD title VARCHAR(255) NOT NULL, ADD resume VARCHAR(500) NOT NULL, ADD image VARCHAR(255) NOT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
