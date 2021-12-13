<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211212093603 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE currency_rate_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE currency_rate (id INT NOT NULL,
                                                   base CHAR(3) NOT NULL,
                                                   quote CHAR(3) NOT NULL,
                                                   datetime TIMESTAMP(0) WITH TIME ZONE NOT NULL,
                                                   rate NUMERIC(8, 4) NOT NULL,
                                                   PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE currency_rate_id_seq CASCADE');
        $this->addSql('DROP TABLE currency_rate');
    }
}
