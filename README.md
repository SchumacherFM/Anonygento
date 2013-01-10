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

This module is optimized to handle a large amount of data.


Random Data
-----------
All data is read from self defined csv files which are stored in the module data folder and there in a locale subfolder.

You provide the random data for the module. There are several files for the english language already available.

Zipcode, City, State and Country aren't anonymized so that shipping and tax calculations still work correctly.

IP addresses in all tables are also anonymized.

You can configure in the backend section which locale to use. Just navigate
to System -> Configuration -> Advanced -> Developer -> Anonygento Settings


Magento Backend hints
---------------------

The red label 'sensitive data' will switch to green for each entity when the anonymization
process ran successful.


Todo / Next Versions
--------------------
- Assign a store view to a locale to get e.g. country specific random names.
- Use backend config to anonymize custom entities instead of creating own modules with observer.
- If the csv files are not found in the locale folder then generate real random strings.
- Enterprise tables like Logging, RMA, Sales credit memo, sales invoice grid, sales order grid, sales shipment grid
- Run via backend instead of shell. Use a nice ajax updater.
- Anonymize all prices


Compatibility
-------------
- Magento >= 1.4
- php >= 5.3.3


Installation Instructions
-------------------------
1. Install modman from https://github.com/colinmollenhour/modman
2. Switch to Magento root folder
3. `modman init`
4. `modman clone git://github.com/SchumacherFM/Anonygento.git`
5. Create yourself some random names or use the English data folder

Demo modules installation

6. `modman link .modman/Anonygento/demo/`


#### Backend

Clear the cache, logout from the admin panel and then login again.

Call the extension from System -> Tools -> Anonygento (just to get a summery).


#### Shell

Call the script like shown below.

##### This is the view if you choose "no".

```
$ php -f shell/anonygento.php
Anonymize this Magento installation? [y/n]n
Nothing done!
$
```

##### This view shows the result for choosing "yes":

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

##### Command line options

Adjusting memory limit (in MB): `php -f anonygento.php -- --memoryLimit=2048 --runAnonymization`

Statistics: `php -f anonygento.php -- --stat`


#### Disabling the confirmation and username query

Add to your e.g. .bash_profile or type it into the shell: `export ANONYGENTO_DEV=true`

This is will enable the dev mode.


Events / Observers
------------------

### Event `anonygento_options_anonymizations_collection_after`

This event will be fired after the collection (`SchumacherFM_Anonygento_Model_Options_Anonymizations`) has been generated.

With this event you can extend the console runner to anonymize custom entities. E.g.: store locator, news
or other payment solutions.

Please see Demo2 module.

Fired in: `SchumacherFM_Anonygento_Model_Options_Anonymizations::getCollection`

Name:       `anonygento_options_anonymizations_collection_after`

Event prefix:  `collection`

Example Observer:

```php

class Namespace_Modul_Model_Observer {

    public function afterOptionsAnonymizationCollection(Varien_Event_Observer $observer)
    {
        $collection = $observer->getEvent()->getCollection();

        $option = array(
          'label' => 'Some label',
          'value' => 'namespace_module/myAnonymizationProcess',
          'model' => 'namespace_module/myModel'
        );

        $collection->addItem(new Varien_Object($option));
    }
}
```

Your model `namespace_module/myModel` must have a resource collection.

Extend your entity with the SQL column: `anonymized TINYINT(1) UNSIGNED NOT NULL DEFAULT 0`. Use the following
setup structure:

#### Setup class in Model/Resource/Setup.php

```php
class Namespace_Modul_Model_Resource_Setup extends SchumacherFM_Anonygento_Model_Resource_Setup {}
```

##### mysql4/install/upgrade

```php
/* @var $installer Namespace_Modul_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

// for non EAV models and to extend EAV _entity table
$installer->addAnonymizedColumn('catalog/product');

// only for EAV models
$installer->addAnonymizedAttribute('catalog_product');

$installer->endSetup();
```

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
        $model->setAnonymized(1);
        $model->save();
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

Example:

```php
class SchumacherFM_Demo1_Model_Observer
{

    public function mappingAfterAlterMyCustomAttribute(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $type  = $event->getType();

        if ($type !== 'Customer') {
            return null;
        }

        $mapped = $event->getMapped();

        $fill = $mapped->getFill();

        // mydemo1 is a custom customer attribute
        $fill['mydemo1'] = array(
            'model'  => 'schumacherfm_demo1/mydemo1',
            'method' => 'changeMydemo1'
        );

        // mydemo2 is a custom customer attribute
        $fill['mydemo2'] = array(
            'method' => 'mt_rand',
            'args'   => array(100, 1000)
        );

        $mapped->setFill($fill);

    }
}

```

This observer extends also the internal Model->getCollection()->add[Attribute|Field]toSelect() method so that
your data will be fetched too.


### Event `anonygento_anonymizations_copy_after`

This event will be fired after data has been copied from the random object to the target object.

This allows you to change the random data for specific models.

See the observer in demo1 module.

Fired in: `SchumacherFM_Anonygento_Model_Anonymizations_Abstract::_copyObjectData`

Name:       `anonygento_anonymizations_copy_after`

Event prefix:  `to_object`

Example:

```php

class SchumacherFM_Demo1_Model_Observer {

    public function copyAfterAlterCustomerTelephone(Varien_Event_Observer $observer)
    {
        $toObject = $observer->getEvent()->getToObject();

        // checking for the correct instance is a must
        if($toObject instanceof Mage_Sales_Model_Order) {
            $toObject->setCustomerTaxvat( mt_rand() );
        }
    }
}
```


Performance
-----------

On my MacBook Air Mid 2012 the whole anonymization process for ~8000 Customers, ~4000 orders
and ~9000 quotes lasts for ~15 minutes. With 256MB of memory limit I have to restart the process
several times.

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
