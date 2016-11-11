<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20161111132657 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('INSERT INTO permission (name) VALUES ("interface:own:write")');
        $this->addSql('INSERT INTO permission (name) VALUES ("interface:own:read")');
        $this->addSql('INSERT INTO permission (name) VALUES ("interface:own:execute")');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DELETE FROM permission WHERE name = "interface:own:write"');
        $this->addSql('DELETE FROM permission WHERE name = "interface:own:read"');
        $this->addSql('DELETE FROM permission WHERE name = "interface:own:execute"');
    }
}
