<?php

/*
 *  Developed by Stefan Matei - stev.matei@gmail.com
 */

namespace Stev\NinjaInvoiceBundle\Lib;

use GuzzleHttp\Client;

/**
 * Description of NinjaInvoice
 *
 * @author stefan
 * 
 * https://www.invoiceninja.com/api-documentation/
 */
class NinjaInvoice {

    protected $baseUri = 'https://app.invoiceninja.com/api/v1';
    protected $apiKey;

    public function __construct($apiKey, $baseUri = null) {
        $this->apiKey = $apiKey;

        if (null != $baseUri) {
            $this->baseUri = $baseUri;
        }
    }

    /**
     * 
     * @param array $parameters An array with ninja parameters
     * @return array
     */
    public function getInvoices(array $parameters = array()) {
        return $this->callEndpoint('invoices', 'GET', $parameters);
    }

    /**
     * 
     * @param array $parameters An array with ninja parameters
     * @return array
     */
    public function getClients(array $parameters = array()) {
        return $this->callEndpoint('clients', 'GET', $parameters);
    }

    /**
     * 
     * @param array $parameters An array with ninja parameters
     * 
     * Possible parameters:[
     *      client_id => integer,
     *      product_key => string,
     *      name => string,
     *      private_notes => string,
     *      first_name => string,
     *      last_name => string,
     *      email_invoice => boolean
     * ]
     * 
     * 
     * @return array
     */
    public function createInvoice(array $parameters = array()) {
        return $this->callEndpoint('invoices', 'POST', $parameters);
    }

    /**
     * 
     * @param array $parameters An array with ninja parameters
     * 
     * Possible parameters:[
     *      name => string,
     *      contact => [ email => string ]
     *      id => integer **If specified and found, it will update the client
     * ]
     * 
     * @return array
     */
    public function createClient(array $parameters = array()) {
        return $this->callEndpoint('clients', 'POST', $parameters);
    }

    /**
     * 
     * @param array $parameters An array with ninja parameters
     * 
     * Possible parameters:[
     *      id => integer
     * ]
     * 
     * @return type
     */
    public function emailInvoice(array $parameters = array()) {
        return $this->callEndpoint('email_invoice', 'POST', $parameters);
    }

    /**
     * 
     * @param string $endpoint Ninja API endpoint
     * @param string $method POST|GET
     * @param array $parameters
     * @return type mixed
     * @throws \Exception
     */
    public function callEndpoint($endpoint, $method = 'POST', array $parameters = array()) {
        $client = new Client();

        //prevent a double slash added when defining a different base URI
        rtrim($this->baseUri, '/');
        $url = $this->baseUri . '/' . $endpoint;

        $options['form_params'] = $parameters;
        $options['headers'] = array(
            'X-Ninja-Token' => $this->apiKey,
            'Content-Type' => 'application/json',
        );

        /* @var $response \GuzzleHttp\Message\ResponseInterface */
        $response = $client->{strtolower($method)}($url, $options);

        $ret = (string) $response->getBody();

        $data = json_decode($ret);

        if (false === $data || null === $data) {
            throw new \Exception('There has been an error in reading the response from the API. Original response: ' . $ret);
        }

        return $data;
    }

}
