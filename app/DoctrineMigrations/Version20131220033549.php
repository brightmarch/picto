<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20131220033549 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("DELETE FROM image WHERE hash = '_not_found'");
    }

    public function down(Schema $schema)
    {
    }
}
