<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230121132431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conges ADD status_id INT NOT NULL');
        $this->addSql('ALTER TABLE conges ADD CONSTRAINT FK_6327DE3A6BF700BD FOREIGN KEY (status_id) REFERENCES status_conges (id)');
        $this->addSql('CREATE INDEX IDX_6327DE3A6BF700BD ON conges (status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conges DROP FOREIGN KEY FK_6327DE3A6BF700BD');
        $this->addSql('DROP INDEX IDX_6327DE3A6BF700BD ON conges');
        $this->addSql('ALTER TABLE conges DROP status_id');
    }
}
