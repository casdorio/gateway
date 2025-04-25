<?php

namespace Casdorio\GatewayPayment\Tests;

use Casdorio\GatewayPayment\Entities\Address;
use Casdorio\GatewayPayment\Entities\Gateway;
use Casdorio\GatewayPayment\Entities\Payment;
use Casdorio\GatewayPayment\Entities\CardInfo;
use Casdorio\GatewayPayment\Entities\Customer;
use Casdorio\GatewayPayment\Entities\Item;
use Casdorio\GatewayPayment\Gateways\AuthorizeNet\AuthorizeNetGateway;
use Casdorio\GatewayPayment\Gateways\AuthorizeNet\RequestBuilderFactory;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Config\Factories;
use Config\Services;

class PaymentGatewayTest extends CIUnitTestCase
{
    // use DatabaseTestTrait; // Remove this trait for now, to simplify.  Add back later if needed.

    private AuthorizeNetGateway $gateway;
    private Payment $payment;
    protected $config;

    protected function setUp(): void
    {
        parent::setUp();

        // Define the environment.
        if (!defined('ENVIRONMENT')) {
            define('ENVIRONMENT', 'testing');
        }

        // 1. Load CodeIgniter's autoloader.  Important!
        require_once realpath(__DIR__ . '/../../vendor/autoload.php');

        // 2.  Manually load essential CodeIgniter files.
        require_once realpath(__DIR__ . '/../../vendor/codeigniter4/framework/system/Config/Services.php');
        require_once realpath(__DIR__ . '/../../vendor/codeigniter4/framework/system/Autoloader/Autoloader.php');
        require_once realpath(__DIR__ . '/../../vendor/codeigniter4/framework/system/Autoloader/Paths.php');


        // 3. Initialize CodeIgniter Services.  Crucial!
        Services::reset(true); // Reset first.
        $this->config = config('App'); // Load config

        // Initialize the autoloader
        $paths = new \Config\Paths();
        $autoloader = new \CodeIgniter\Autoloader\Autoloader($paths);
        $autoloader->initialize(config('Autoload'));
        Services::autoloader($autoloader);


        // Boot the framework.
        $app = Services::codeigniter();
        $app->initialize();


        // 4. Set up the service providers.  This is what was likely missing.
        Services::request();
        Services::response();
        Services::uri();
        Services::routes();
        Services::session();
        Services::validation();
        Services::security();
        Services::logger();
        Services::timer();
        Services::benchmark();
        Factories::reset();  // Reset factories.


        // Configuration
        $gatewayData = new Gateway(
            'AuthorizeNet',
            '6f2Q5UKj49KZ',
            '3t4e29Amt874TGPX',
            true
        );

        $requestBuilderFactory = new RequestBuilderFactory();
        $this->gateway = new AuthorizeNetGateway($gatewayData, $requestBuilderFactory);

        // Test Data
        $cardInfo = new CardInfo(
            '4007000000027',
            '1225',
            '123'
        );

        $customer = new Customer(
            email: 'john.doe@example.com',
            merchantCustomerId: '12345',
            firstName: 'John',
            lastName: 'Doe',
            phone: '555-123-4567'
        );

        $billingAddress = new Address(
            address: '123 Main St',
            city: 'Anytown',
            state: 'CA',
            zip_code: '90210',
            country: 'USA'
        );

        $deliveryAddress = new Address(
            address: '456 Oak Ave',
            city: 'Othertown',
            state: 'NY',
            zip_code: '10001',
            country: 'USA'
        );

        $item = new Item(
            '1',
            'Produto de Teste',
            1,
            'Produto de Teste',
            10.00
        );

        $items = [$item];

        $this->payment = new Payment(
            amount: 10.00,
            card_info: $cardInfo,
            customer: $customer,
            invoice_number: 'INV-123',
            description: null,
            billing_address: $billingAddress,
            delivery_address: $deliveryAddress,
            items: null
        );
    }

    public function testChargeCreditCard(): void
    {
        $response = $this->gateway->chargeCreditCard($this->payment);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('transaction_id', $response);
        $this->assertNotEmpty($response['transaction_id']);
    }

    public function testAuthorizeCreditCard(): void
    {
        $response = $this->gateway->authorize($this->payment);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('transaction_id', $response);
        $this->assertNotEmpty($response['transaction_id']);
        $this->assertArrayHasKey('auth_code', $response);
        $this->assertNotEmpty($response['auth_code']);
    }

    public function testCaptureAuthorizedTransaction(): void
    {
        $authResponse = $this->gateway->authorize($this->payment);
        $this->assertEquals('success', $authResponse['status']);
        $transactionId = $authResponse['transaction_id'];

        $captureResponse = $this->gateway->capture($transactionId, $this->payment->amount);

        $this->assertIsArray($captureResponse);
        $this->assertArrayHasKey('status', $captureResponse);
        $this->assertEquals('success', $captureResponse['status']);
        $this->assertArrayHasKey('transaction_id', $captureResponse);
        $this->assertNotEmpty($captureResponse['transaction_id']);
    }
}