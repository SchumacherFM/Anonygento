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
- Quotes
- Quote Addresses
- Newsletter Subscribers
- Diverse Grid Collections
- Fires three events


Random Data
-----------
All data comes from self defined csv files. You provide the random data for the module.
There are several files for the english language already provided.

Zipcode, City, State and Country aren't anonymized so that shipping and tax calculations still work correctly.

This module is optimized to handle a large amount of data with less memory.


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
