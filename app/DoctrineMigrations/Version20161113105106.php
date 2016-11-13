<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;


class Version20161113105106 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('DELETE FROM permission WHERE name = "appsetting:users:modify"');
        $this->addSql('DELETE FROM permission WHERE name = "appsetting:permissions:modify"');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('INSERT INTO permission (name) VALUES ("appsetting:users:modify")');
        $this->addSql('INSERT INTO permission (name) VALUES ("appsetting:permissions:modify")');
    }
}
