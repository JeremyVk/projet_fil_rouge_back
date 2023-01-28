<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230128145500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_book_variant DROP FOREIGN KEY FK_66A303518D9F6D38');
        $this->addSql('DROP TABLE order_book_variant');
        $this->addSql('ALTER TABLE order_item ADD price INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_book_variant (order_id INT NOT NULL, book_variant_id INT NOT NULL, INDEX IDX_66A30351A4C4E31C (book_variant_id), INDEX IDX_66A303518D9F6D38 (order_id), PRIMARY KEY(order_id, book_variant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE order_book_variant ADD CONSTRAINT FK_66A303518D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_item DROP price');
    }
}
