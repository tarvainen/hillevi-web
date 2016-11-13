<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20161113095032 extends AbstractMigration
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

        $this->addSql('INSERT INTO permission (name) VALUES ("cron:read")');
        $this->addSql('INSERT INTO permission (name) VALUES ("cron:write")');

        $this->addSql('INSERT INTO permission (name) VALUES ("import:execute")');
        $this->addSql('INSERT INTO permission (name) VALUES ("notification:all")');

        $this->addSql('INSERT INTO permission (name) VALUES ("usersettings:application")');

        $this->addSql('INSERT INTO permission (name) VALUES ("appsetting:permissions:write")');
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

        $this->addSql('DELETE FROM permission WHERE name = "cron:read"');
        $this->addSql('DELETE FROM permission WHERE name = "cron:write"');

        $this->addSql('DELETE FROM permission WHERE name = "import:execute"');
        $this->addSql('DELETE FROM permission WHERE name = "notification:all"');

        $this->addSql('DELETE FROM permission WHERE name = "usersettings:application"');

        $this->addSql('DELETE FROM permission WHERE name = "appsetting:permissions:write"');
    }
}
