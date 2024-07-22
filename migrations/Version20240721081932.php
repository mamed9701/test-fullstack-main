<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240721081932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clocking ADD date DATE NOT NULL');
        $this->addSql('ALTER TABLE clocking ADD CONSTRAINT FK_D3E9DCCD4431A71B FOREIGN KEY (clocking_project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE clocking_user ADD CONSTRAINT FK_A197F1EAB6D103F FOREIGN KEY (clocking_id) REFERENCES clocking (id)');
        $this->addSql('ALTER TABLE clocking_user ADD CONSTRAINT FK_A197F1EAA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP date');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clocking DROP FOREIGN KEY FK_D3E9DCCD4431A71B');
        $this->addSql('ALTER TABLE clocking DROP date');
        $this->addSql('ALTER TABLE clocking_user DROP FOREIGN KEY FK_A197F1EAB6D103F');
        $this->addSql('ALTER TABLE clocking_user DROP FOREIGN KEY FK_A197F1EAA76ED395');
        $this->addSql('ALTER TABLE `user` ADD date DATE NOT NULL');
    }
}
