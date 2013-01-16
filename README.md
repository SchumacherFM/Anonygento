Anonygento
==========

Anonymizes all customer related data in a Magento shop!

Install (via modman) and run:
```
$ cd shell
$ php -f anonygento.php
```

Anonygento is a Magento module which anonymizes all data especially customer, orders, quotes, newsletters, etc.

This anonymization is useful when you want to hire external developers or for your internal developers who really
not need any kind of sensitive data.

Anonygento can be easily extended by custom configuration and observers.

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
- php >= 5.3.5

I'm using http://php-osx.liip.ch/ with version 5.4.10 and 5.3.19.


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

In this grid view you can view the data from all entities by clicking on the "View" link.

#### Shell

Call the script like shown below.

##### This is the view if you choose "no".

```
$ cd shell
$ php -f anonygento.php
Anonymize this Magento installation? [y/n]n
Nothing done!
$
```

##### This view shows the result for choosing "yes":

```
$ cd shell
$ php -f anonygento.php
Anonymize this Magento installation? [y/n]y
Running customer, work load: XXXX rows
  0% [-------------------------------------]
Running ...
```

##### Command line options

Adjusting memory limit (in MB): `php -f anonygento.php -- --memoryLimit=2048 --runAnonymization`

Statistics: `php -f anonygento.php -- --stat`

If all fails and you still get our of memory errors:

Limiting the collection size: `php -f anonygento.php -- --collectionLimit=400 --runAnonymization`

#### Disabling the confirmation and username query

Add to your e.g. .bash_profile or type it into the shell: `export ANONYGENTO_DEV=true`

This is will enable the dev mode.


Extending the anonymization process
-----------------------------------

#### Custom attributes

To anonymize custom attributes in the e.g. customer eav model you can add the following
xml to the config.xml. No PHP programming is necessary except if you need custom random strings.

```xml
<config>
    <anonygento>
        <anonymizations>
            <!--extending the customer anonymization process with two custom attributes-->
            <customer>
                <mapping>
                    <fill>
                        <myAttributeName1>
                            <model>schumacherfm_demo1/mydemo1</model>
                            <method>changeMydemo1</method>
                        </myAttributeName1>
                        <myAttributeName2>
                            <method>mt_rand</method>
                            <args>
                                <a0>100</a0>
                                <a1>1000</a1>
                            </args>
                        </myAttributeName2>
                        <myAttributeName3>
                            <helper>core</helper>
                            <method>getRandomString</method>
                            <args>
                                <a0>12</a0>
                            </args>
                        </myAttributeName3>
                    </fill>
                </mapping>
            </customer>
        </anonymizations>
    </anonygento>
</config>
```

Please see Demo1 module.


#### Custom entities

Adding custom entities for anonymization you can simply extend the config.xml.

With this additional config you can extend the console runner to anonymize custom entities. E.g.: store locator, news
or other payment solutions.

Please see Demo2 module.

#### Example config:

```xml
<config>
    <anonygento>
        <anonymizations>
            <myAnonymizationKey>
                <active>1</active>
                <label>Some label</label>
                <model>namespace2_moduleX/aModel</model>
                <adminRoute>yourAdminRoute/[edit|view|etc]?id</adminRoute><!-- if available -->
                <options>
                    <optionKey1>1</optionKey1>
                    <!-- your options -->
                </options>
                <anonymizationModel>namespace_module/myAnonymizationProcess</anonymizationModel>
            </myAnonymizationKey>
        </anonymizations>
    </anonygento>
</config>
```

Your model `namespace2_moduleX/aModel` must have a resource collection.

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
$installer->addAnonymizedColumn('namespace2_moduleX/aModel');

// only for EAV models, catalog_product is for example.
$installer->addAnonymizedAttribute('catalog_product');

$installer->endSetup();
```

Example Model:

```php

class Namespace_Module_Model_MyAnonymizationProcess extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{

    public function run()
    {
        parent::run($this->_getCollection(), '_anonymizeFooBar');
    }

    protected function _anonymizeFooBar($model)
    {
        // to access options: (see above in the config)
        $opt1 = $this->_getOption('optionKey1','[int|bool|str]');

        // please see Demo2 module
        $this->_copyObjectData( ... );
        $model->save();
    }
    /**
     * @return Namespace_Module_Model_Resource_Name_Collection
     */
    protected function _getCollection()
    {
        return parent::_getCollection('namespace_module/name', 'myAnonymizationKey');
    }

}
```



Events / Observers
------------------

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


Performance / Errors
--------------------

On my MacBook Air Mid 2012 the whole anonymization process for ~8000 Customers, ~4000 orders
and ~9000 quotes lasts for ~15 minutes. With 256MB of memory limit I have to restart the process
several times. Setting the memory limit to 2048 the script runs without interruptions.

If you get errors like this one:

`Fatal error: Allowed memory size of xxx bytes exhausted (tried to allocate x bytes) in abc.php on line x`

Just rerun the script.

On a Ubuntu virtual machine with standard LAMP system (php 5.3.[10|20] Suhosin) the script fails.
Even with memory limit -1 the script will not run. Maybe someone can test this without Suhosin ...

If the script still fails set this config.xml option to 0 (zero):

`global->anonygento->anonymizations->customer->options->anonymizeOrder = 0`

`global->anonygento->anonymizations->customer->options->anonymizeQuote = 0`


Support / Contribution
----------------------

Report a bug or send me a pull request.


Other modules for Magento
-------------------------

There is https://github.com/integer-net/Anonymizer but is has several limitations.


Licence
-------
[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)
