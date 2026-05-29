<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260529081058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notifications ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW() NOT NULL');
        $this->addSql('ALTER TABLE notifications ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE notifications ADD sender_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3F624B39D FOREIGN KEY (sender_id) REFERENCES "user" (id)');
        $this->addSql('CREATE INDEX IDX_6000B0D3F624B39D ON notifications (sender_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notifications DROP CONSTRAINT FK_6000B0D3F624B39D');
        $this->addSql('DROP INDEX IDX_6000B0D3F624B39D');
        $this->addSql('ALTER TABLE notifications DROP created_at');
        $this->addSql('ALTER TABLE notifications DROP sender_id');
    }
}
