<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260511145224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE documents DROP CONSTRAINT fk_a2b072889d86650f');
        $this->addSql('DROP INDEX idx_a2b072889d86650f');
        $this->addSql('ALTER TABLE documents RENAME COLUMN user_id_id TO user_id');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B07288A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_A2B07288A76ED395 ON documents (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE documents DROP CONSTRAINT FK_A2B07288A76ED395');
        $this->addSql('DROP INDEX IDX_A2B07288A76ED395');
        $this->addSql('ALTER TABLE documents RENAME COLUMN user_id TO user_id_id');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT fk_a2b072889d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_a2b072889d86650f ON documents (user_id_id)');
    }
}
