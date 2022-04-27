# omnipay-paymentsense-connect-e

**Javascript gateway driver for Paymentsense's Connect-E service**

Omnipay implementation of Paymentsense's Connect-E gateway.

See [Paymentsense documentation](https://docs.connect.paymentsense.cloud/ConnectE/GettingStarted) for more details.

## Installation

This driver is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "digitickets/omnipay-paymentsense-connect-e": "^1.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## What's Included

This driver allows you to retrieve an authcode using a JWT key, and once the transaction is complete, to check the validity of the payment at the gateway.

It also supports refunds of partial and full amounts.

It only handles card payments.

## What's Not Included

This driver does not handle any of the other card management operations, such as subscriptions (repeat payments).

## Basic Usage

For general Omnipay usage instructions, please see the main [Omnipay](https://github.com/omnipay/omnipay)
repository.

First call the authorize() endpoint. Then call getTransactionReference() on the response. This will get you the authcode for passing into the connect-e javascript SDK.

Once the customer has paid via the SDK, you should call acceptNotification() to check that the payment was complete. Pass in the same authcode you got from authorize() in as a transactionReference param. This will return a transactionReference param, which can be used in the refund() call if
required (note this is also known as crossReference or Request ID).

### Required Parameters

You must pass the following parameters into the driver when calling `authorize()`, `acceptNotification()` or `refund()`. You will be sent these by the Paymentsense onboarding team.

```
jwt
merchantUrl
```

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you believe you have found a bug in this driver, please report it using the [GitHub issue tracker](https://github.com/digitickets/omnipay-pay360-hosted-cashier/issues),
or better yet, fork the library and submit a pull request.
