Anonygento
==========

Anonymizes all customer related data in a Magento shop!

Anonygento is a Magento module which anonymizes all data especially customer, orders, newsletters. This anonymization
is useful when you want to hire external developers. Even useful for your internal developers who really not need
any kind of live data.


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
- Diverse Grid Collections

@Todo Enterprise tables like RMA, Sales credit memo, sales invoice grid, sales order grid,
sales shipment grid


Random Data
-----------
All data comes from self defined csv files which are store in the data folder.
You provide the random data for the module. There are several files for the
english language already provided.

@todo if the csv file is empty then a random string is generated.

Zipcode, City, State and Country aren't anonymized so that shipping and tax calculations
still work correctly.

This module is optimized to handle a large amount of data.


Events
------

First event will be fired after data has been copied from the random object.
This allows you to change for specific entities the data.
to the object for anonymization.

Name:       `anonygento_anonymizations_copy_after`
Arguments:  `copied_object` and `mappings`

Example:

```php

class XXX_YYY_Model_Observer {

    public function afterObjectCopy(Varien_Event_Observer $observer)
    {
        $copiedObject = $observer->getEvent()->getCopiedObject();

        if($copiedObject->getBillingName()){
            $copiedObject->setBillingName( 'Lorem ipsum' );
        }

    }
}
```

Second event will be fired after getCustomer() has been called on the random customer
model. This allows you to change specific random data for all entities.
Name:       `anonygento_random_customer_getcustomer_after`
Arguments:  `customer`

Example:

```php

class XXX_YYY_Model_Observer {

    public function afterCustomerCalled(Varien_Event_Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();

        if($customer->getTelephone()){
            $customer->setTelephone( '0000000000000' );
        }

    }
}
```

Third event: add your own table and mappings
@todo implement



Todo / Next Versions
--------------------
- Run via backend instead of shell
- Enterprise tables
- If no csv file fo a locale is found then generate real random strings
- Anonymize all prices


Compatibility
-------------
- Magento >= 1.4
- php >= 5.3.3


Installation Instructions
-------------------------
1.  Git clone it somewhere, symlink it into your Magento installation. (Script will be provided soon)
2.  Clear the cache, logout from the admin panel and then login again.
3.  Call the extension from from System -> Tools -> Anonygento (Currently not supported in version 0.0.1).
3b. Call the extension via shell in the sit directory: php -f shell/anonygento.php


Shell
-----

Call the script like shown below. This is the view if you choose "no".

```
$ php -f shell/anonygento.php
Anonymize this Magento installation? [y/n]n
Nothing done!
$
```

This view shows the result for choosing "yes":

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

How to handle the observer?
--------------------------
There are three events which will be fired in different places.
@todo describe them


Support / Contribution
----------------------
Report a bug or send me a pull request.


Other modules for Magento
-------------------------
There is https://github.com/integer-net/Anonymizer but is has serveral limitations


Licence
-------
[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)
