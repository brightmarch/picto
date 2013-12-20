<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20131212211121 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO image (created_at, updated_at, status, raw_hash, hash, file_name, file_size, file_extension, width, height, content_type, remote_url) VALUES (current_timestamp(0), current_timestamp(0), 1, '4cabb40704ee02f6d13c469377928baa8ece8e96', '_not_found', 'not-found.png', 38963, 'png', 600, 600, 'image/png', 'https://s3.amazonaws.com/picto.images/_not_found.png')");
    }

    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM image WHERE hash = '_not_found'");
    }
}
