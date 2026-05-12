<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260512145043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE absences DROP CONSTRAINT fk_f9c0efff16e5e825');
        $this->addSql('ALTER TABLE absences DROP CONSTRAINT fk_f9c0efff9d86650f');
        $this->addSql('DROP INDEX idx_f9c0efff16e5e825');
        $this->addSql('DROP INDEX idx_f9c0efff9d86650f');
        $this->addSql('ALTER TABLE absences RENAME COLUMN user_id_id TO user_id');
        $this->addSql('ALTER TABLE absences RENAME COLUMN document_id_id TO document_id');
        $this->addSql('ALTER TABLE absences ADD CONSTRAINT FK_F9C0EFFFA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE absences ADD CONSTRAINT FK_F9C0EFFFC33F7837 FOREIGN KEY (document_id) REFERENCES documents (id)');
        $this->addSql('CREATE INDEX IDX_F9C0EFFFA76ED395 ON absences (user_id)');
        $this->addSql('CREATE INDEX IDX_F9C0EFFFC33F7837 ON absences (document_id)');
        $this->addSql('ALTER TABLE promtion_users DROP CONSTRAINT fk_d2a3546f1f42ea0a');
        $this->addSql('ALTER TABLE promtion_users DROP CONSTRAINT fk_d2a3546f9d86650f');
        $this->addSql('DROP INDEX idx_d2a3546f1f42ea0a');
        $this->addSql('DROP INDEX idx_d2a3546f9d86650f');
        $this->addSql('ALTER TABLE promtion_users ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE promtion_users ADD promotion_id INT NOT NULL');
        $this->addSql('ALTER TABLE promtion_users DROP user_id_id');
        $this->addSql('ALTER TABLE promtion_users DROP promotion_id_id');
        $this->addSql('ALTER TABLE promtion_users ADD CONSTRAINT FK_D2A3546FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE promtion_users ADD CONSTRAINT FK_D2A3546F139DF194 FOREIGN KEY (promotion_id) REFERENCES promotions (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_D2A3546FA76ED395 ON promtion_users (user_id)');
        $this->addSql('CREATE INDEX IDX_D2A3546F139DF194 ON promtion_users (promotion_id)');
        $this->addSql('ALTER TABLE user_actions DROP CONSTRAINT fk_5d45efe59d86650f');
        $this->addSql('DROP INDEX idx_5d45efe59d86650f');
        $this->addSql('ALTER TABLE user_actions RENAME COLUMN user_id_id TO user_id');
        $this->addSql('ALTER TABLE user_actions ADD CONSTRAINT FK_5D45EFE5A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_5D45EFE5A76ED395 ON user_actions (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE absences DROP CONSTRAINT FK_F9C0EFFFA76ED395');
        $this->addSql('ALTER TABLE absences DROP CONSTRAINT FK_F9C0EFFFC33F7837');
        $this->addSql('DROP INDEX IDX_F9C0EFFFA76ED395');
        $this->addSql('DROP INDEX IDX_F9C0EFFFC33F7837');
        $this->addSql('ALTER TABLE absences RENAME COLUMN user_id TO user_id_id');
        $this->addSql('ALTER TABLE absences RENAME COLUMN document_id TO document_id_id');
        $this->addSql('ALTER TABLE absences ADD CONSTRAINT fk_f9c0efff16e5e825 FOREIGN KEY (document_id_id) REFERENCES documents (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE absences ADD CONSTRAINT fk_f9c0efff9d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_f9c0efff16e5e825 ON absences (document_id_id)');
        $this->addSql('CREATE INDEX idx_f9c0efff9d86650f ON absences (user_id_id)');
        $this->addSql('ALTER TABLE promtion_users DROP CONSTRAINT FK_D2A3546FA76ED395');
        $this->addSql('ALTER TABLE promtion_users DROP CONSTRAINT FK_D2A3546F139DF194');
        $this->addSql('DROP INDEX IDX_D2A3546FA76ED395');
        $this->addSql('DROP INDEX IDX_D2A3546F139DF194');
        $this->addSql('ALTER TABLE promtion_users ADD user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE promtion_users ADD promotion_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE promtion_users DROP user_id');
        $this->addSql('ALTER TABLE promtion_users DROP promotion_id');
        $this->addSql('ALTER TABLE promtion_users ADD CONSTRAINT fk_d2a3546f1f42ea0a FOREIGN KEY (promotion_id_id) REFERENCES promotions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE promtion_users ADD CONSTRAINT fk_d2a3546f9d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d2a3546f1f42ea0a ON promtion_users (promotion_id_id)');
        $this->addSql('CREATE INDEX idx_d2a3546f9d86650f ON promtion_users (user_id_id)');
        $this->addSql('ALTER TABLE user_actions DROP CONSTRAINT FK_5D45EFE5A76ED395');
        $this->addSql('DROP INDEX IDX_5D45EFE5A76ED395');
        $this->addSql('ALTER TABLE user_actions RENAME COLUMN user_id TO user_id_id');
        $this->addSql('ALTER TABLE user_actions ADD CONSTRAINT fk_5d45efe59d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_5d45efe59d86650f ON user_actions (user_id_id)');
    }
}
