<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221210104409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_variant DROP FOREIGN KEY FK_6263973B16A2B381');
        $this->addSql('DROP INDEX IDX_6263973B16A2B381 ON book_variant');
        $this->addSql('ALTER TABLE book_variant ADD parent_id INT DEFAULT NULL, DROP book_id');
        $this->addSql('ALTER TABLE book_variant ADD CONSTRAINT FK_6263973B727ACA70 FOREIGN KEY (parent_id) REFERENCES book (id)');
        $this->addSql('CREATE INDEX IDX_6263973B727ACA70 ON book_variant (parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_variant DROP FOREIGN KEY FK_6263973B727ACA70');
        $this->addSql('DROP INDEX IDX_6263973B727ACA70 ON book_variant');
        $this->addSql('ALTER TABLE book_variant ADD book_id INT NOT NULL, DROP parent_id');
        $this->addSql('ALTER TABLE book_variant ADD CONSTRAINT FK_6263973B16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6263973B16A2B381 ON book_variant (book_id)');
    }
}
