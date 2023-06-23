<?php

namespace App\Tests;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\HttpKernel\KernelInterface;

class DatabasePrimer
{
	public static function prime(KernelInterface $kernel)
    {
        if ('test' !== $kernel->getEnvironment()) {
            throw new \LogicException('Primer must be executed in the test environment');
        }

        $entityManaer = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $metadatas = $entityManaer->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManaer);
        $schemaTool->updateSchema($metadatas);
    }
}