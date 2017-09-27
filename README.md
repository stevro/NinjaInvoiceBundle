# NinjaInvoiceBundle
Symfony 2 Bundle for Ninja Invoice API

#Use the invoice ninja sdk outside of Symfony
Simply download only the NinjaInvoice.php from Lib folder. It has no dependency on Symfony
Make sure you have Guzzle library installed as well.

## Update notice 27.09.2017 - Breaking Changes
#I have updated this bundle on 27.09.2017 to support the upgrade of invoice ninja from v2.4.3 to v2.9.5.
If you have are using this bundle for older versions do not upgrade it!

There is also an official php-sdk but I do not recommend it yet. It has some bugs that can be very annoying, so for the moment you are better of writing your own integration.

If anyone has the time, I'm sure the guys from invoice ninja will apreciate any help in improving the sdk and API.

## 1. Installation:
Add this to composer.json
<pre><code>
"stev/ninja-invoice-bundle": "0.1.*@dev"
</code></pre>

Add this in app/AppKernel.php
<pre><code>
new Stev\NinjaInvoiceBundle\StevListaFirmeBundle()
</code></pre>

Add this in app/config.yml
<pre><code>
stev_ninja_invoice:
    apiKey: YOUR_API_KEY
    baseUri: OPTIONAL. ONLY SET IT IF YOU USE THE SELF HOSTED VERSION OF NINJA INVOICE
</code></pre>

## 2. Usage
<pre><code>
/* @var $ninjaInvoice \Stev\NinjaInvoiceBundle\Lib\NinjaInvoice */
        $ninjaInvoice = $this->get('stev.ninja_invoice');
        $response = $ninjaInvoice->createClient();
</code></pre>

Ninja Invoice API documentation can be found at https://www.invoiceninja.com/api-documentation/
