<?php

use PHPUnit\Framework\TestCase;

require_once 'ddapi/ApiHandler.php'; // Include the class file

class ApiHandlerTest extends TestCase
{
    private $apiHandler;

    protected function setUp(): void
    {
        $this->apiHandler = new ApiHandler();
    }

    public function testHandleApiRequestWithValidApiKeyAndExistingApi()
    {
        $_GET['api'] = 'characters';
        $_GET['api_key'] = 'your_api_key1';

        ob_start();
        $this->apiHandler->handleApiRequest('characters');
        $output = ob_get_clean();

        $this->assertStringContainsString('"CRAdvancement"', $output);
        $this->assertStringNotContainsString('"error"', $output);
        $this->assertStringContainsString('"CRAdvancement":', $output);
    }

    public function testHandleApiRequestWithInvalidApiKey()
    {
        $_GET['api'] = 'characters';
        $_GET['api_key'] = 'invalid_api_key';

        ob_start();
        $this->apiHandler->handleApiRequest('characters');
        $output = ob_get_clean();

        $this->assertStringContainsString('"error"', $output);
        $this->assertStringContainsString('"Invalid API key"', $output);
    }

    public function testHandleApiRequestWithMissingApiKey()
    {
        $_GET['api'] = 'characters';

        ob_start();
        $this->apiHandler->handleApiRequest('characters');
        $output = ob_get_clean();

        $this->assertStringContainsString('"error"', $output);
        $this->assertStringContainsString('"API key is required"', $output);
    }

    public function testHandleApiRequestWithNonExistingApi()
    {
        $_GET['api'] = 'non_existing_api';
        $_GET['api_key'] = 'your_api_key1';

        ob_start();
        $this->apiHandler->handleApiRequest('non_existing_api');
        $output = ob_get_clean();

        $this->assertStringContainsString('"error"', $output);
        $this->assertStringContainsString('"API not found"', $output);
    }
}
