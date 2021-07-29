<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Core\Ads\Domain\InitializeCarModelsTrait;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

final class Version20210728235455 extends AbstractMigration
{
    use InitializeCarModelsTrait;

    public function getDescription() : string
    {
        return 'Add car model referential';
    }

    public function up(Schema $schema) : void
    {
        $models = iterator_to_array($this->buildCarModels());
        foreach ($models as $m) {
            $this->addSql(sprintf('insert into ads_car_model (id_id, name, manufacturer) values (\'%s\', \'%s\', \'%s\');', $m->getId(), $m->getName(), $m->getManufacturer()));
        }
    }

    public function down(Schema $schema) : void
    {
    }
}
