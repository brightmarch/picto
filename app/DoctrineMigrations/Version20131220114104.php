<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20131220114104 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE image ADD COLUMN job_id character varying(96)");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE image DROP COLUMN job_id");
    }
}
