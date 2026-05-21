<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260519085555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE projects_promotions (projects_id INT NOT NULL, promotions_id INT NOT NULL, PRIMARY KEY (projects_id, promotions_id))');
        $this->addSql('CREATE INDEX IDX_5CA4A1CE1EDE0F55 ON projects_promotions (projects_id)');
        $this->addSql('CREATE INDEX IDX_5CA4A1CE10007789 ON projects_promotions (promotions_id)');
        $this->addSql('ALTER TABLE projects_promotions ADD CONSTRAINT FK_5CA4A1CE1EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projects_promotions ADD CONSTRAINT FK_5CA4A1CE10007789 FOREIGN KEY (promotions_id) REFERENCES promotions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE documents ALTER type DROP NOT NULL');
        $this->addSql('ALTER TABLE documents ALTER path DROP NOT NULL');
        $this->addSql('ALTER TABLE projects DROP CONSTRAINT fk_5c93b3a4139df194');
        $this->addSql('DROP INDEX idx_5c93b3a4139df194');
        $this->addSql('ALTER TABLE projects DROP promotion_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projects_promotions DROP CONSTRAINT FK_5CA4A1CE1EDE0F55');
        $this->addSql('ALTER TABLE projects_promotions DROP CONSTRAINT FK_5CA4A1CE10007789');
        $this->addSql('DROP TABLE projects_promotions');
        $this->addSql('ALTER TABLE documents ALTER type SET NOT NULL');
        $this->addSql('ALTER TABLE documents ALTER path SET NOT NULL');
        $this->addSql('ALTER TABLE projects ADD promotion_id INT NOT NULL');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT fk_5c93b3a4139df194 FOREIGN KEY (promotion_id) REFERENCES promotions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_5c93b3a4139df194 ON projects (promotion_id)');
    }
}
