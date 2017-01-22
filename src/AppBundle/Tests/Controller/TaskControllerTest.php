<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testTasks()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/tasks');
    }

}
