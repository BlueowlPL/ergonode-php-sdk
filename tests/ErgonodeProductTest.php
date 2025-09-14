<?php


use Ergonode\Builders\ProductBuilder;
use Ergonode\ErgonodeClient;
use PHPUnit\Framework\TestCase;

class ErgonodeProductTest extends TestCase
{
    private ErgonodeClient $ergonodeClient;

    public function setUp(): void
    {
        $this->ergonodeClient = new ErgonodeClient(getenv('ERGO_API_BASE_URL'), getenv('ERGO_API_TOKEN'));
    }

    public function testListProducts(): void
    {
        $this->assertInstanceOf(ProductBuilder::class, $this->ergonodeClient->products());

        $products = $this->ergonodeClient->products()->perPage(10)->get();

        $this->assertIsObject($products);
        $this->assertFalse($products->isError());
        $this->assertNotEmpty($products->getData());

//        error_log(print_r($products->getData(), true));
    }

    public function testListProductsPagination(): void
    {
        $this->assertInstanceOf(ProductBuilder::class, $this->ergonodeClient->products());

        $builder = $this->ergonodeClient->products()->perPage(10);
        $products_1 = $builder->get();
        $products_2 = $builder->get();

        $this->assertNotEquals($products_1->getData(), $products_2->getData());
    }

    public function testSingleProduct(): void
    {
        $this->assertInstanceOf(ProductBuilder::class, $this->ergonodeClient->products());

        $products = $this->ergonodeClient->products()->perPage(1)->get();

        $sku = $products->getData()[0]->getCode();
        $product = $this->ergonodeClient->products()->single($sku)->get();

        $this->assertIsObject($product);
        $this->assertFalse($product->isError());
        $this->assertNotEmpty($product->getData());
        error_log(print_r($product, true));
    }
}