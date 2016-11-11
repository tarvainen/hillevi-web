<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20161111133443 extends AbstractMigration
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

        $this->addSql('INSERT INTO permission (name) VALUES ("appsetting:users:read")');
        $this->addSql('INSERT INTO permission (name) VALUES ("appsetting:users:write")');
        $this->addSql('INSERT INTO permission (name) VALUES ("appsetting:users:delete")');
        $this->addSql('INSERT INTO permission (name) VALUES ("appsetting:users:modify")');
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

        $this->addSql('DELETE FROM permission WHERE name = "appsetting:users:read"');
        $this->addSql('DELETE FROM permission WHERE name = "appsetting:users:write"');
        $this->addSql('DELETE FROM permission WHERE name = "appsetting:users:delete"');
        $this->addSql('DELETE FROM permission WHERE name = "appsetting:users:modify"');
    }
}
