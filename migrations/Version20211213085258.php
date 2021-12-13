<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211213085258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE currency_rate ALTER COLUMN id SET DEFAULT nextval(\'currency_rate_id_seq\')');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE currency_rate ALTER COLUMN id DROP DEFAULT');
    }
}
