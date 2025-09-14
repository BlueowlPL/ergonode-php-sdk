<?php

namespace Ergonode\Tests;

use Ergonode\Builders\LanguageBuilder;
use Ergonode\ErgonodeClient;
use PHPUnit\Framework\TestCase;

class ErgonodeLanguageTest extends TestCase
{
    private ErgonodeClient $ergonodeClient;

    public function setUp(): void
    {
        $this->ergonodeClient = new ErgonodeClient(getenv('ERGO_API_BASE_URL'), getenv('ERGO_API_TOKEN'));
    }

    public function testListLanguages(): void
    {
        $this->assertInstanceOf(LanguageBuilder::class, $this->ergonodeClient->languages());

        $languages = $this->ergonodeClient->languages()->get();

        $this->assertIsObject($languages);
        $this->assertFalse($languages->isError());
        $this->assertNotEmpty($languages->getData());

//        error_log(print_r($languages, true));
    }
}