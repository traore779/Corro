<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200810081330 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classe (id INT AUTO_INCREMENT NOT NULL, niveau_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_8F87BF96B3E9C81 (niveau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE classe ADD CONSTRAINT FK_8F87BF96B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE matiere DROP FOREIGN KEY FK_9014574AB3E9C81');
        $this->addSql('DROP INDEX IDX_9014574AB3E9C81 ON matiere');
        $this->addSql('ALTER TABLE matiere CHANGE niveau_id classe_id INT NOT NULL');
        $this->addSql('ALTER TABLE matiere ADD CONSTRAINT FK_9014574A8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('CREATE INDEX IDX_9014574A8F5EA509 ON matiere (classe_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matiere DROP FOREIGN KEY FK_9014574A8F5EA509');
        $this->addSql('DROP TABLE classe');
        $this->addSql('DROP INDEX IDX_9014574A8F5EA509 ON matiere');
        $this->addSql('ALTER TABLE matiere CHANGE classe_id niveau_id INT NOT NULL');
        $this->addSql('ALTER TABLE matiere ADD CONSTRAINT FK_9014574AB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9014574AB3E9C81 ON matiere (niveau_id)');
    }
}
