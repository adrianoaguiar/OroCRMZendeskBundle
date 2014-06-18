<?php

namespace OroCRM\Bundle\ZendeskBundle\Tests\Unit\Model;

use OroCRM\Bundle\ZendeskBundle\Model\RestClientFactory;

class RestClientFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetRestClient()
    {
        $settings = array(
            'email' => 'test@mail.com',
            'api_token' => uniqid(),
            'sub_domain' => 'domain'
        );
        $provider = $this->getMockBuilder('OroCRM\Bundle\ZendeskBundle\Provider\ConfigurationProvider')
            ->disableOriginalConstructor()
            ->getMock();
        $provider->expects($this->once())
            ->method('getEmail')
            ->will($this->returnValue($settings['email']));
        $provider->expects($this->once())
            ->method('getApiToken')
            ->will($this->returnValue($settings['api_token']));
        $provider->expects($this->once())
            ->method('getSubDomain')
            ->will($this->returnValue($settings['sub_domain']));
        $factory = new RestClientFactory($provider);
        $restClient = $factory->getRestClient();
        $this->assertInstanceOf('OroCRM\Bundle\ZendeskBundle\Model\RestClient', $restClient);
        $this->assertEquals($settings, $restClient->getSettings());
    }
}
