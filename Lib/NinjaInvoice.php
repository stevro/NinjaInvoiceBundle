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
class NinjaInvoice
{

    protected $baseUri = 'https://app.invoiceninja.com/api/v1';
    protected $apiKey;

    public function __construct($apiKey, $baseUri = null)
    {
        $this->apiKey = $apiKey;

        if (null != $baseUri) {
            $this->baseUri = $baseUri;
        }
    }
    
    /**
     * Will return the account based on the api token
     * @param array $parameters
     * @return array
     */
    public function getAccount(array $parameters = array()){
        $result = $this->callEndpoint('accounts', 'GET', $parameters);

        if (isset($result['data'])) {
            return $result['data'];
        }

        return array();
    }
    
    /**
     * 
     * @param array $parameters An array with ninja parameters
     * @return array
     */
    public function getInvoices(array $parameters = array())
    {
        if (!isset($parameters['page'])) {
            $parameters['page'] = 1;
        }

        $results = $this->callEndpoint('invoices', 'GET', $parameters);

        $invoices = $results['data'];
        $meta = $results['meta'];

        if (isset($meta['pagination'])) {
            if ((int) $meta['pagination']['current_page'] < (int) $meta['pagination']['total_pages']) {
                $parameters['page'] = (int) $meta['pagination']['current_page'] + 1;
                $clients = array_merge($invoices, $this->callEndpoint('invoices', 'GET', $parameters));
            }
        }

        return $invoices;
    }

    /**
     * 
     * @param array $parameters An array with ninja parameters
     * @return array
     * 
     * A client structure
     * "account_key" => "6ylCO7cuY7qUhOriWuiQTaNBigusx2Iq"
      "is_owner" => true
      "id" => 30
      "name" => "Dickens, Walter and Rohan SRL"
      "balance" => 0
      "paid_to_date" => 0
      "updated_at" => 1447948634
      "archived_at" => null
      "address1" => """
      town East Elzashire Str 30703 Kirlin Camp\n
      Elfriedabury, AZ 90150-3632 21
      """
      "address2" => null
      "city" => "East Elzashire"
      "state" => "East Elzashire"
      "postal_code" => "-"
      "country_id" => 642
      "work_phone" => null
      "private_notes" => null
      "last_login" => null
      "website" => null
      "industry_id" => 0
      "size_id" => 0
      "is_deleted" => false
      "payment_terms" => 0
      "vat_number" => "23523"
      "id_number" => "J40/3975/2014"
      "language_id" => 0
      "currency_id" => 0
      "custom_value1" => null
      "custom_value2" => null
      "contacts" => array:1 [▼
      0 => array:12 [▼
      "account_key" => "6ylCO7cuY7qUhOriWuiQTaNBigusx2Iq"
      "is_owner" => true
      "id" => 30
      "first_name" => "traj"
      "last_name" => "artj"
      "email" => "stefan+erhe@nimasoftware.com"
      "updated_at" => 1506343400
      "archived_at" => null
      "is_primary" => true
      "phone" => null
      "last_login" => null
      "send_invoice" => true
      ]
      ]
     */
    public function getClients(array $parameters = array())
    {

        if (!isset($parameters['page'])) {
            $parameters['page'] = 1;
        }

        $results = $this->callEndpoint('clients', 'GET', $parameters);

        $clients = $results['data'];
        $meta = $results['meta'];

        if (isset($meta['pagination'])) {
            if ((int) $meta['pagination']['current_page'] < (int) $meta['pagination']['total_pages']) {
                $parameters['page'] = (int) $meta['pagination']['current_page'] + 1;
                $clients = array_merge($clients, $this->getClients($parameters));
            }
        }

        return $clients;
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
    public function createInvoice(array $parameters = array())
    {
        $result = $this->callEndpoint('invoices', 'POST', $parameters);

        if (isset($result['data'])) {
            return $result['data'];
        }

        return array();
    }
    
    public function getTaxRates(array $parameters = array()){
        if (!isset($parameters['page'])) {
            $parameters['page'] = 1;
        }

        $results = $this->callEndpoint('tax_rates', 'GET', $parameters);

        $rates = $results['data'];
        $meta = $results['meta'];

        if (isset($meta['pagination'])) {
            if ((int) $meta['pagination']['current_page'] < (int) $meta['pagination']['total_pages']) {
                $parameters['page'] = (int) $meta['pagination']['current_page'] + 1;
                $rates = array_merge($rates, $this->getTaxRates($parameters));
            }
        }

        return $rates;
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
    public function createClient(array $parameters = array())
    {
        $result = $this->callEndpoint('clients', 'POST', $parameters);
        if (isset($result['data'])) {
            return $result['data'];
        }

        return array();
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
    public function emailInvoice(array $parameters = array())
    {
        return $this->callEndpoint('email_invoice', 'POST', $parameters);
    }

    /**
     * 
     * @param string $endpoint Ninja API endpoint
     * @param string $method POST|GET
     * @param array $parameters
     * @param bool $assoc True if you want an assoc array as response, false to get stdObject
     * @return type mixed
     * @throws \Exception
     */
    public function callEndpoint($endpoint, $method = 'POST', array $parameters = array(), $assoc = true)
    {
        $client = new Client();

        //prevent a double slash added when defining a different base URI
        rtrim($this->baseUri, '/');
        $url = $this->baseUri . '/' . $endpoint;

        $options['body'] = json_encode($parameters);
        $options['headers'] = array(
            'X-Ninja-Token' => $this->apiKey,
            'Content-Type' => 'application/json',
        );

        /* @var $response \GuzzleHttp\Message\ResponseInterface */
        $response = $client->{strtolower($method)}($url, $options);

        $ret = (string) $response->getBody();

        $data = json_decode($ret, $assoc);

        if (false === $data || null === $data) {
            throw new \Exception('There has been an error in reading the response from the API. Original response: ' . $ret);
        }

        return $data;
    }

}
