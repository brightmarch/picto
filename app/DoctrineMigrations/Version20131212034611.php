<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20131212034611 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE image (
                id serial NOT NULL,
                created_at timestamp without time zone NOT NULL,
                updated_at timestamp without time zone NOT NULL,
                status smallint NOT NULL DEFAULT 0,
                raw_hash character varying(96) NOT NULL,
                hash character varying(32) NOT NULL,
                file_name character varying(256) NOT NULL,
                file_size integer NOT NULL DEFAULT 0,
                file_extension character varying(12) NOT NULL,
                width integer NOT NULL DEFAULT 0,
                height integer NOT NULL DEFAULT 0,
                content_type character varying(64) NOT NULL,
                upload_time integer NOT NULL DEFAULT 0,
                memory_usage decimal(8, 2) NOT NULL DEFAULT 0.0,
                remote_url text,
                CONSTRAINT image_pkey PRIMARY KEY (id)
            ) WITH (OIDS=FALSE)
        ");

        $this->addSql("CREATE INDEX image_status_idx ON image (status)");
        $this->addSql("CREATE UNIQUE INDEX hash_idx ON image (hash)");
    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP TABLE IF EXISTS image CASCADE");
    }
}
