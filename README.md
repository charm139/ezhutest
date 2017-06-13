# ezhu api接口

## Installation

**install with composer**
```php
composer require ezhu-erp-sdk/wisderapi
```

## Usage
**example**

```php
use $wisderapi
$data=array(
   'token'     =>'qw1321qs2wqe',
   'secret'    =>'dfdsf12312das123',
   'url'       =>'http://60.174.195.124:6041/router/rest',
   );
$wisderapi = new wisderapi($data);
$params = array(
 'method'=>'trade.list',
 'start_time'=>'2017-02-11 13:03:41',
 'end_time'=>'2017-06-09 17:03:41',
 'shop_type_ids'=>'1,2,3',
 'page_no'=>'1',
 'page_size'=>'50',
 );
 $result = $wisderapi->send($params);
var_dump($result);
exit;
```
