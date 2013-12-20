<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20131216164607 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE image ADD COLUMN file_path text");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE image DROP COLUMN file_path");
    }
}
