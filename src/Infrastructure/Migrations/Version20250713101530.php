<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250713101530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ALTER google_client_id DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER google_client_secret DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER google_redirect_uri DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" ALTER google_client_id SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER google_client_secret SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER google_redirect_uri SET NOT NULL');
    }
}
