<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260512141457 extends AbstractMigration
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
        $this->addSql('ALTER TABLE grades DROP CONSTRAINT fk_3ae361106c1197c9');
        $this->addSql('ALTER TABLE grades DROP CONSTRAINT fk_3ae36110f773e7ca');
        $this->addSql('DROP INDEX idx_3ae361106c1197c9');
        $this->addSql('DROP INDEX idx_3ae36110f773e7ca');
        $this->addSql('ALTER TABLE grades ADD project_id INT NOT NULL');
        $this->addSql('ALTER TABLE grades ADD student_id INT NOT NULL');
        $this->addSql('ALTER TABLE grades DROP project_id_id');
        $this->addSql('ALTER TABLE grades DROP student_id_id');
        $this->addSql('ALTER TABLE grades ADD CONSTRAINT FK_3AE36110166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE grades ADD CONSTRAINT FK_3AE36110CB944F1A FOREIGN KEY (student_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_3AE36110166D1F9C ON grades (project_id)');
        $this->addSql('CREATE INDEX IDX_3AE36110CB944F1A ON grades (student_id)');
        $this->addSql('ALTER TABLE notification_recipients DROP CONSTRAINT fk_ef1497e6b9f07bae');
        $this->addSql('ALTER TABLE notification_recipients DROP CONSTRAINT fk_ef1497e69d86650f');
        $this->addSql('DROP INDEX idx_ef1497e6b9f07bae');
        $this->addSql('DROP INDEX idx_ef1497e69d86650f');
        $this->addSql('ALTER TABLE notification_recipients ADD notification_id INT NOT NULL');
        $this->addSql('ALTER TABLE notification_recipients ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE notification_recipients DROP notification_id_id');
        $this->addSql('ALTER TABLE notification_recipients DROP user_id_id');
        $this->addSql('ALTER TABLE notification_recipients ADD CONSTRAINT FK_EF1497E6EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notifications (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE notification_recipients ADD CONSTRAINT FK_EF1497E6A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_EF1497E6EF1A9D84 ON notification_recipients (notification_id)');
        $this->addSql('CREATE INDEX IDX_EF1497E6A76ED395 ON notification_recipients (user_id)');
        $this->addSql('ALTER TABLE projects DROP CONSTRAINT fk_5c93b3a4d33488f');
        $this->addSql('DROP INDEX idx_5c93b3a4d33488f');
        $this->addSql('ALTER TABLE projects RENAME COLUMN prmotion_id_id TO promotion_id');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4139DF194 FOREIGN KEY (promotion_id) REFERENCES promotions (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_5C93B3A4139DF194 ON projects (promotion_id)');
        $this->addSql('ALTER TABLE promotions DROP CONSTRAINT fk_ea1b3034f73333ac');
        $this->addSql('DROP INDEX idx_ea1b3034f73333ac');
        $this->addSql('ALTER TABLE promotions RENAME COLUMN professor_id_id TO professor_id');
        $this->addSql('ALTER TABLE promotions ADD CONSTRAINT FK_EA1B30347D2D84D5 FOREIGN KEY (professor_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_EA1B30347D2D84D5 ON promotions (professor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE documents DROP CONSTRAINT FK_A2B07288A76ED395');
        $this->addSql('DROP INDEX IDX_A2B07288A76ED395');
        $this->addSql('ALTER TABLE documents RENAME COLUMN user_id TO user_id_id');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT fk_a2b072889d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_a2b072889d86650f ON documents (user_id_id)');
        $this->addSql('ALTER TABLE grades DROP CONSTRAINT FK_3AE36110166D1F9C');
        $this->addSql('ALTER TABLE grades DROP CONSTRAINT FK_3AE36110CB944F1A');
        $this->addSql('DROP INDEX IDX_3AE36110166D1F9C');
        $this->addSql('DROP INDEX IDX_3AE36110CB944F1A');
        $this->addSql('ALTER TABLE grades ADD project_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE grades ADD student_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE grades DROP project_id');
        $this->addSql('ALTER TABLE grades DROP student_id');
        $this->addSql('ALTER TABLE grades ADD CONSTRAINT fk_3ae361106c1197c9 FOREIGN KEY (project_id_id) REFERENCES projects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grades ADD CONSTRAINT fk_3ae36110f773e7ca FOREIGN KEY (student_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_3ae361106c1197c9 ON grades (project_id_id)');
        $this->addSql('CREATE INDEX idx_3ae36110f773e7ca ON grades (student_id_id)');
        $this->addSql('ALTER TABLE notification_recipients DROP CONSTRAINT FK_EF1497E6EF1A9D84');
        $this->addSql('ALTER TABLE notification_recipients DROP CONSTRAINT FK_EF1497E6A76ED395');
        $this->addSql('DROP INDEX IDX_EF1497E6EF1A9D84');
        $this->addSql('DROP INDEX IDX_EF1497E6A76ED395');
        $this->addSql('ALTER TABLE notification_recipients ADD notification_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE notification_recipients ADD user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE notification_recipients DROP notification_id');
        $this->addSql('ALTER TABLE notification_recipients DROP user_id');
        $this->addSql('ALTER TABLE notification_recipients ADD CONSTRAINT fk_ef1497e6b9f07bae FOREIGN KEY (notification_id_id) REFERENCES notifications (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification_recipients ADD CONSTRAINT fk_ef1497e69d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_ef1497e6b9f07bae ON notification_recipients (notification_id_id)');
        $this->addSql('CREATE INDEX idx_ef1497e69d86650f ON notification_recipients (user_id_id)');
        $this->addSql('ALTER TABLE projects DROP CONSTRAINT FK_5C93B3A4139DF194');
        $this->addSql('DROP INDEX IDX_5C93B3A4139DF194');
        $this->addSql('ALTER TABLE projects RENAME COLUMN promotion_id TO prmotion_id_id');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT fk_5c93b3a4d33488f FOREIGN KEY (prmotion_id_id) REFERENCES promotions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_5c93b3a4d33488f ON projects (prmotion_id_id)');
        $this->addSql('ALTER TABLE promotions DROP CONSTRAINT FK_EA1B30347D2D84D5');
        $this->addSql('DROP INDEX IDX_EA1B30347D2D84D5');
        $this->addSql('ALTER TABLE promotions RENAME COLUMN professor_id TO professor_id_id');
        $this->addSql('ALTER TABLE promotions ADD CONSTRAINT fk_ea1b3034f73333ac FOREIGN KEY (professor_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_ea1b3034f73333ac ON promotions (professor_id_id)');
    }
}
