<?php


use Ergonode\Builders\TemplateBuilder;
use Ergonode\ErgonodeClient;
use PHPUnit\Framework\TestCase;

class ErgonodeTemplateTest extends TestCase
{
    private ErgonodeClient $ergonodeClient;

    public function setUp(): void
    {
        $this->ergonodeClient = new ErgonodeClient(getenv('ERGO_API_BASE_URL'), getenv('ERGO_API_TOKEN'));
    }

    public function testListTemplates(): void
    {
        $this->assertInstanceOf(TemplateBuilder::class, $this->ergonodeClient->templates());

        $templates = $this->ergonodeClient->templates()->perPage(1)->get();

        $this->assertIsObject($templates);
        $this->assertFalse($templates->isError());
        $this->assertNotEmpty($templates->getData());

//        error_log(print_r($templates->getData(), true));
    }
}