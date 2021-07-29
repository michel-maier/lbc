<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210728234734 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Initial schema';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE ads_ad (id_id UUID NOT NULL, title VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, discriminant VARCHAR(255) NOT NULL, PRIMARY KEY(id_id))');
        $this->addSql('COMMENT ON COLUMN ads_ad.id_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE ads_automobile_ad (id_id UUID NOT NULL, model VARCHAR(255) NOT NULL, manufacturer VARCHAR(255) NOT NULL, PRIMARY KEY(id_id))');
        $this->addSql('COMMENT ON COLUMN ads_automobile_ad.id_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE ads_car_model (id_id UUID NOT NULL, name VARCHAR(255) NOT NULL, manufacturer VARCHAR(255) NOT NULL, PRIMARY KEY(id_id))');
        $this->addSql('COMMENT ON COLUMN ads_car_model.id_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE ads_job_ad (id_id UUID NOT NULL, PRIMARY KEY(id_id))');
        $this->addSql('COMMENT ON COLUMN ads_job_ad.id_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE ads_real_estate_ad (id_id UUID NOT NULL, PRIMARY KEY(id_id))');
        $this->addSql('COMMENT ON COLUMN ads_real_estate_ad.id_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE ads_automobile_ad ADD CONSTRAINT FK_212F34107F449E57 FOREIGN KEY (id_id) REFERENCES ads_ad (id_id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ads_job_ad ADD CONSTRAINT FK_5999BDBF7F449E57 FOREIGN KEY (id_id) REFERENCES ads_ad (id_id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ads_real_estate_ad ADD CONSTRAINT FK_44FCABCA7F449E57 FOREIGN KEY (id_id) REFERENCES ads_ad (id_id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ads_automobile_ad DROP CONSTRAINT FK_212F34107F449E57');
        $this->addSql('ALTER TABLE ads_job_ad DROP CONSTRAINT FK_5999BDBF7F449E57');
        $this->addSql('ALTER TABLE ads_real_estate_ad DROP CONSTRAINT FK_44FCABCA7F449E57');
        $this->addSql('DROP TABLE ads_ad');
        $this->addSql('DROP TABLE ads_automobile_ad');
        $this->addSql('DROP TABLE ads_car_model');
        $this->addSql('DROP TABLE ads_job_ad');
        $this->addSql('DROP TABLE ads_real_estate_ad');
    }
}
