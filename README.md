# Fondy

Fondy payment gateway integration.

## Installation

### Manually

* Create a free Fondy account https://portal.fondy.eu/mportal/ 
* Copy the module folder into the ```<thelia_root>/local/modules/``` directory and make sure that the name of the folder is ```Fondy``` or create zip archive which contains folder ```Fondy/{all files from this repository}``` and install from administration side.
* Activate Fondy in your Thelia administration panel.

### Composer

Add it in your main Thelia composer.json file

```
composer require cloudipsp/thelia:~1.0
```

### How to use

To use this module, your first need to activate if in the Back-office, tab Modules,
then click on "Configure" on the Fondy module line. Enter your Merchant_id, secret_key and save.
