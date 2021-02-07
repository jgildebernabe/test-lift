<?php

declare(strict_types=1);

namespace Bodas\Tests\functional\UI\Http\Controllers\Health;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class HealthControllerTest extends WebTestCase
{
    public function testHealth(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');

        $client->request(Request::METHOD_GET, $router->generate('health'));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}
