# NinjaInvoiceBundle
Symfony 2 Bundle for Ninja Invoice API

#Use the invoice ninja sdk outside of Symfony
Simply download only the NinjaInvoice.php from Lib folder. It has no dependency on Symfony

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
