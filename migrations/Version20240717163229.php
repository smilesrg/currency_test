<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240717163229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Created `currency` and `rate` tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE currency (code VARCHAR(3) NOT NULL, name VARCHAR(255) NOT NULL, symbol VARCHAR(3) NOT NULL, PRIMARY KEY(code))');
        $this->addSql('CREATE TABLE rate (base_currency VARCHAR(3) NOT NULL, target_currency VARCHAR(3) NOT NULL, rate DOUBLE PRECISION NOT NULL, last_updated DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , PRIMARY KEY(base_currency, target_currency), CONSTRAINT FK_DFEC3F3923BD2BC7 FOREIGN KEY (base_currency) REFERENCES currency (code) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_DFEC3F39B3FD5856 FOREIGN KEY (target_currency) REFERENCES currency (code) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_DFEC3F3923BD2BC7 ON rate (base_currency)');
        $this->addSql('CREATE INDEX IDX_DFEC3F39B3FD5856 ON rate (target_currency)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE rate');
    }
}
