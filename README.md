Anonygento
==========

Anonymizes all customer related data in a Magento shop!

Anonygento is a Magento module which anonymizes all data especially customer, orders, quotes, newsletters, etc.
This anonymization is useful when you want to hire external developers or for your internal developers who really
not need any kind of live data.

Anonygento can be easily extended by custom events / observers.

Do not run this script in the production environment.


About
-----
- version: 0.0.1
- extension key: SchumacherFM_Anonygento
- [extension on GitHub](https://github.com/SchumacherFM/Anonygento)
- [direct download link](https://github.com/SchumacherFM/Anonygento/tarball/master)
- Integrates parts of the Zend Framework 2


What it does?
-------------
This extension anonymizes all customer related data from the following data objects:
- Customers
- Customers Addresses
- Orders
- Order Addresses
- Order Grid
- Order Payment
- Quotes
- Quote Addresses
- Quote Payment
- Credit memo
- Invoice
- Shipment
- Newsletter Subscribers
- Gift messages
- Review
- Rating
- Sendfriend

@Todo Enterprise tables like Logging, RMA, Sales credit memo, sales invoice grid, sales order grid,
sales shipment grid


Random Data
-----------
All data comes from self defined csv files which are store in the data folder.
You provide the random data for the module. There are several files for the
english language already provided.

@todo if the csv file is empty then a random string is generated.

Zipcode, City, State and Country aren't anonymized so that shipping and tax calculations
still work correctly.

IP addresses in all tables are also anonymized.

This module is optimized to handle a large amount of data.


Events / Observers
------------------

### Event `anonygento_options_anonymizations_collection_after`

This event will be fired after the collection for the whole anonymization process has been generated.
That means you can extend the console runner to anonymize custom entities. E.g.: store locator, news
or other payment solutions.

Fired in: `SchumacherFM_Anonygento_Model_Options_Anonymizations::getCollection`

Name:       `anonygento_options_anonymizations_collection_after`

Event prefix:  `collection`

Example Observer:

```php

class XXX_YYY_Model_Observer {

    public function afterOptionsAnonymizationCollection(Varien_Event_Observer $observer)
    {
        $collection = $observer->getEvent()->getCollection();

        $option = array(
          'label' => 'Some label'
          'value' => 'namespaceModuleEntity'
          'model' => 'namespace_module/myAnonymizationProcess'
        );

        $customProcess = new Varien_Object($option);
        $customProcess
            ->setStatus( @todo )
            ->setUnanonymized( (int)[number of unanonymized rows] )
            ->setAnonymized( (int)[number of anonymized rows] );

        $collection->addItem($customProcess);
    }
}
```

Extend your entity with the SQL column: `anonymized TINYINT(1) UNSIGNED NOT NULL DEFAULT 0`

Example Model:

```php

class Namespace_Module_Model_MyAnonymizationProcess extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{

    public function run()
    {
        $collection = Mage::getModel('namespace_module/name')->getCollection();

        $i = 0;
        foreach ($collection as $model)
        {
            $this->_anonymizeFooBar($model);
            $this->getProgressBar()->update($i);
            $i++;
        }
        $this->getProgressBar()->finish();
    }

    protected function _anonymizeFooBar($model)
    {
        // your code
    }

}
```

A real world example follows soon.


### Event `anonygento_anonymizations_get_mapping_after`

If you have custom columns/attributes in your tables/entities then you can anonymize them here.

Please see demo1 module.

Fired in: `SchumacherFM_Anonygento_Model_Anonymizations_Abstract::_getMappings`

Name:       `anonygento_anonymizations_get_mapping_after`

Event prefix:  `type` and `mapped`


### Event `anonygento_anonymizations_copy_after`

This event will be fired after data has been copied from the random object to the target object.

This allows you to change the random data for specific models.

See the observer in demo1 module.

Fired in: `SchumacherFM_Anonygento_Model_Anonymizations_Abstract::_copyObjectData`

Name:       `anonygento_anonymizations_copy_after`

Event prefix:  `to_object`

Example:

```php

class XXX_YYY_Model_Observer {

    public function afterObjectCopy(Varien_Event_Observer $observer)
    {
        $toObject = $observer->getEvent()->getToObject();

        if($toObject instanceof Mage_Sales_Model_Order){
            $toObject->setCustomerTaxvat( mt_rand() );
        }

    }
}
```


Todo / Next Versions
--------------------
1. If the csv files are not found in the locale folder then generate real random strings
2. Enterprise tables
3. Anonymize all prices
4. Run via backend instead of shell. Use a nice ajax updater


Compatibility
-------------
- Magento >= 1.4
- php >= 5.3.3


Installation Instructions
-------------------------
1. Git clone it somewhere, copy/symlink it into your Magento installation. (Script will be provided soon)
2. Clear the cache, logout from the admin panel and then login again.
3. Call the extension from from System -> Tools -> Anonygento
4. Call the extension via shell in the `site` directory: `php -f shell/anonygento.php`


Shell
-----

Call the script like shown below.

#### This is the view if you choose "no".

```
$ php -f shell/anonygento.php
Anonymize this Magento installation? [y/n]n
Nothing done!
$
```

#### This view shows the result for choosing "yes":

```
$ php -f anonygento.php
Anonymize this Magento installation? [y/n]y
Admin user name: [username]
Admin password: ***********
Welcome firstname lastname
Running customer, work load: XXXX rows
  0% [-------------------------------------]
Running ...
```

The admin password is shown in clear text ... no hidden input :-( but there are also nice colors :-)

#### Command line options

Adjusting memory limit: `php -f anonygento.php -- --memoryLimit=2048 --runAnonymization`

Statistics: `php -f anonygento.php -- --stat`


#### Disabling the confirmation and username query

Add to your e.g. .bash_profile or type it into the shell: `export ANONYGENTO_DEV=true`

This is will enable the dev mode.


Performance
-----------

On my MacBook Air Mid 2012 the whole anonymization process for ~8000 Customers, ~4000 orders
and ~9000 quotes lasts for `x` minutes.

If you get errors like this one:

`Fatal error: Allowed memory size of xxx bytes exhausted (tried to allocate x bytes) in abc.php on line x`

Just rerun the script.

Support / Contribution
----------------------
Report a bug or send me a pull request.


Other modules for Magento
-------------------------
There is https://github.com/integer-net/Anonymizer but is has several limitations.


Licence
-------
[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)
