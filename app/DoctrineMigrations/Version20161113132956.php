<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20161113132956 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('INSERT INTO permission (name) VALUES ("usersettings")');
        $this->addSql('DELETE FROM permission WHERE name = "usersettings:application"');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DELETE FROM permission WHERE name = "usersettings"');
        $this->addSql('INSERT INTO permission (name) VALUES ("usersettings:application")');
    }
}
