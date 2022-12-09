<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221209160359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book_format (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book_format_book_variant (book_format_id INT NOT NULL, book_variant_id INT NOT NULL, INDEX IDX_81DD8012C487E043 (book_format_id), INDEX IDX_81DD8012A4C4E31C (book_variant_id), PRIMARY KEY(book_format_id, book_variant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book_format_book_variant ADD CONSTRAINT FK_81DD8012C487E043 FOREIGN KEY (book_format_id) REFERENCES book_format (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book_format_book_variant ADD CONSTRAINT FK_81DD8012A4C4E31C FOREIGN KEY (book_variant_id) REFERENCES book_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book DROP isbn_number, DROP unit_price, DROP stock');
        $this->addSql('ALTER TABLE book_variant ADD isbn_number VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_format_book_variant DROP FOREIGN KEY FK_81DD8012C487E043');
        $this->addSql('ALTER TABLE book_format_book_variant DROP FOREIGN KEY FK_81DD8012A4C4E31C');
        $this->addSql('DROP TABLE book_format');
        $this->addSql('DROP TABLE book_format_book_variant');
        $this->addSql('ALTER TABLE book_variant DROP isbn_number');
        $this->addSql('ALTER TABLE book ADD isbn_number INT NOT NULL, ADD unit_price DOUBLE PRECISION NOT NULL, ADD stock INT NOT NULL');
    }
}
