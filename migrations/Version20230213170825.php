<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230213170825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_variant ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE book_variant ADD CONSTRAINT FK_6263973B727ACA70 FOREIGN KEY (parent_id) REFERENCES book (id)');
        $this->addSql('CREATE INDEX IDX_6263973B727ACA70 ON book_variant (parent_id)');
        $this->addSql('ALTER TABLE shop_product_variant DROP FOREIGN KEY FK_C969A029727ACA70');
        $this->addSql('DROP INDEX IDX_C969A029727ACA70 ON shop_product_variant');
        $this->addSql('ALTER TABLE shop_product_variant DROP parent_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_variant DROP FOREIGN KEY FK_6263973B727ACA70');
        $this->addSql('DROP INDEX IDX_6263973B727ACA70 ON book_variant');
        $this->addSql('ALTER TABLE book_variant DROP parent_id');
        $this->addSql('ALTER TABLE shop_product_variant ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_product_variant ADD CONSTRAINT FK_C969A029727ACA70 FOREIGN KEY (parent_id) REFERENCES shop_product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_C969A029727ACA70 ON shop_product_variant (parent_id)');
    }
}
