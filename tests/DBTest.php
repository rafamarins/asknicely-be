<?php

namespace App\Tests;

use App\DB\Database;
use PHPUnit\Framework\TestCase;

class DbTest extends TestCase
{
    public function test_connection_success()
    {
        $db = Database::getInstance();

        $this->assertNotNull($db);
    }

    public function test_connection_fail()
    {
        $db = null;

        $this->assertNull($db);
    }
}
