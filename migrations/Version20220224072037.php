<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220224072037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP INDEX UNIQ_BA388B79395C3F3, ADD INDEX IDX_BA388B79395C3F3 (customer_id)');
        $this->addSql('ALTER TABLE cart CHANGE customer_id customer_id INT NOT NULL');
        $this->addSql('ALTER TABLE product CHANGE name name VARCHAR(200) NOT NULL, CHANGE code code VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP INDEX IDX_BA388B79395C3F3, ADD UNIQUE INDEX UNIQ_BA388B79395C3F3 (customer_id)');
        $this->addSql('ALTER TABLE cart CHANGE customer_id customer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE product CHANGE code code VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
