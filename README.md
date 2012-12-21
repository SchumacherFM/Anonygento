Anonygento
==========

Anonymizes all customer related data!

Anonygento is a Magento module which anonymizes all data especially customer, orders, newsletters. This anonymization
is useful when you want to hire external developers. Even useful for your internal developers who really not need
any kind of live data.

About
-----
- version: 0.0.1
- extension key: SchumacherFM_Anonygento
- [extension on GitHub](https://github.com/SchumacherFM/Anonygento)
- [direct download link](https://github.com/SchumacherFM/Anonygento/tarball/master)

What it does?
-----------
This extension anonymizes all customer related data from the following data objects:
- Customers
- Customers Addresses
- Orders
- Order Addresses
- Quotes
- Quote Addresses
- Newsletter Subscribers
- Fires three events

All data comes from http://fakester.biz. So you need an internet connection.

Zipcode, City, State and Country aren't anonymized so that shipping and tax calculations still work correctly.

This module is optimized to handle a large amount of data with less memory.


Todo / Next Versions
-------------
- Run via backend instead of shell
- Anonymize all prices


Compatibility
-------------
- Magento >= 1.4


Installation Instructions
-------------------------
1.  Git clone it somewhere, symlink it into your Magento installation. (Script will be provided soon)
2.  Clear the cache, logout from the admin panel and then login again.
3.  Call the extension from from System -> Tools -> Anonygento (Currently not supported in version 0.0.1).
3b. Call the extension via shell: php -f shell/anonygento.php


Support / Contribution
------------
Report a bug or send me a pull request.


Licence
-------
[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)

WTF?
-------
After starting developing on a shell extension I've found this module: https://github.com/integer-net/Anonymizer

But bummer:
- It can't handle a large amount of data
- Fake data is taken from external sources. I mainly develop offline during a long train ride
- PHP code of the module is suboptimal and not Magento style
- bugs

So I've refactored everything ... still in refactoring.
