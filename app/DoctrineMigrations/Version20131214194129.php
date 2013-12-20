<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20131214194129 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE image ADD COLUMN view_count integer NOT NULL DEFAULT 0");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE image DROP COLUMN view_count");
    }
}
