<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260522120845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE grades ADD promotion_id INT NOT NULL');
        $this->addSql('ALTER TABLE grades ADD CONSTRAINT FK_3AE36110139DF194 FOREIGN KEY (promotion_id) REFERENCES promotions (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_3AE36110139DF194 ON grades (promotion_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE grades DROP CONSTRAINT FK_3AE36110139DF194');
        $this->addSql('DROP INDEX IDX_3AE36110139DF194');
        $this->addSql('ALTER TABLE grades DROP promotion_id');
    }
}
