<?php

use SimpleSAML\Store;

class idp_setup_test extends advanced_testcase
{
    public function setUp(): void
    {
        require_once dirname(dirname(dirname(__FILE__))) . '/setup.php';
    }

    public function test_sql_connects()
    {
        $store = Store::getInstance();

        $this->assertInstanceOf('SimpleSAML\Store\SQL', $store);
    }
}