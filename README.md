At the moment this module is only thought to be used as one for Magento2 and using its rest API. However you are always welcome to develop a controller or additional functionality to it.

How to use this module:

Configure OpenPay Payment keys.
-------------------------------

Enter into Magento2 admin panel and go to `Stores > Configuration > Sales > Payment Methods > OpenPay` and **enable** the payment method, then enter all the information provided by OpenPay in the correct fields. Also select **yes** from `Modo pruebas (Sandbox)` if you want to use sandbox credentials or **no** if you want to use production credentials.

Create a card
--------------

In order to create a card, first you will need to create a card token [see how](http://www.openpay.mx/docs/openpayjs.html) as well as a device session id [see how](http://www.openpay.mx/en/docs/fraud-tool.html) in the frontend.

Then you can either create the card at the time of creating a new customer [POST] or by updating the customer [PUT]. In any case all you will need to do is send an additional `Card` object as an `extension_attribute`. The only way to create a card using this module is by token. So you need to send a blank `Card` object filling only the `token` and the `device_session_id`.

> **Note:** This module looks for all the new tokens sent as part of the `extension_attributes` and it creates the cards according those tokens.

Here are two examples of how to create a card, one using the POST call and another using a PUT call:

### `[POST] /rest/v1/customers`

### Request

**Body:**

```
{
    "customer": {
        "email": "example@domain.com",
        "firstname": "Pedro",
        "lastname": "Juárez",
        "storeId": 1,
        "websiteId": 1,
        "addresses": [
            {
                "country_id": "MX",
                "street": [
                  "Some address 23"
                ],
                "telephone": "5555555558",
                "postcode": "09823",
                "city": "México",
                "firstname": "Pedro",
                "lastname": "Juárez",
                "default_shipping": true,
                "default_billing": true
            }
        ],
        "extension_attributes": {
          "openpay_card": [
            {
              "token": null,
              "device_session_id": null
          ]
        }
    },
    "password": "a_Wi3rd_P4zzw0rt"
}
```
### Response

```
{
  "id": 1,
  "group_id": 1,
  "default_billing": "1",
  "default_shipping": "1",
  "created_at": "2015-12-15 00:44:20",
  "updated_at": "2016-01-04 22:19:24",
  "created_in": "Default Store View",
  "email": "example@domain.com",
  "firstname": "Pedro",
  "lastname": "Juárez",
  "store_id": 1,
  "website_id": 1,
  "addresses": [
    {
      "id": 1,
      "customer_id": 1,
      "region": {
        "region_code": null,
        "region": null,
        "region_id": 0
      },
      "region_id": 0,
      "country_id": "MX",
      "street": [
        "Some address 23"
      ],
      "telephone": "5555555558",
      "postcode": "09823",
      "city": "México",
      "firstname": "Pedro",
      "lastname": "Juárez",
      "default_shipping": true,
      "default_billing": true
    }
  ],
  "disable_auto_group_change": 0,
  "extension_attributes": {
    "openpay_card": [
      {
        "customer_id": "atxdxf8dtsfiqj6jlykp",
        "card_id": "krotlgka8xqeebtxpjf9",
        "created_at": "2016-01-04T16:18:32-06:00",
        "updated_at": null,
        "token": null,
        "device_session_id": null,
        "type": "debit",
        "brand": "visa",
        "card_number": "411111XXXXXX1111",
        "holder_name": "Pedro Juarez Ramirez",
        "expiration_year": "20",
        "expiration_month": "12",
        "allows_charges": false,
        "allows_payouts": true,
        "bank_name": "Banamex",
        "bank_code": "002",
        "address": {
          "city": "Querétaro",
          "country_code": "MX",
          "line1": "Av 5 de Febrero",
          "line2": "Roble 207",
          "line3": "col carrillo",
          "postal_code": "76900",
          "state": "Queretaro"
        }
      }
    ]
  },
  "custom_attributes": [
    {
      "attribute_code": "openpay_customer_id",
      "value": "atxdxfodtsfiqj4jlykp"
    }
  ]
}
```

### `[PUT] /rest/v1/customers/me`

### Request
**Body:**

```
{
    "customer": {
        "id": 1,
        "email": "example@domain.com",
        "firstname": "Pedro",
        "lastname": "Juárez",
        "storeId": 1,
        "websiteId": 1,
        "addresses": [
            {
                "country_id": "MX",
                "street": [
                  "Some address 23"
                ],
                "telephone": "5555555558",
                "postcode": "09823",
                "city": "México",
                "firstname": "Pedro",
                "lastname": "Juárez",
                "default_shipping": true,
                "default_billing": true
            }
        ],
        "extension_attributes": {
          "openpay_card": [
            {
              "token": null,
              "device_session_id": null
            }
          ]
        }
    }
}
```

### Response

```
{
  "id": 1,
  "group_id": 1,
  "default_billing": "1",
  "default_shipping": "1",
  "created_at": "2015-12-15 00:44:20",
  "updated_at": "2016-01-04 22:19:24",
  "created_in": "Default Store View",
  "email": "example@domain.com",
  "firstname": "Pedro",
  "lastname": "Juárez",
  "store_id": 1,
  "website_id": 1,
  "addresses": [
    {
      "id": 1,
      "customer_id": 1,
      "region": {
        "region_code": null,
        "region": null,
        "region_id": 0
      },
      "region_id": 0,
      "country_id": "MX",
      "street": [
        "Some address 23"
      ],
      "telephone": "5555555558",
      "postcode": "09823",
      "city": "México",
      "firstname": "Pedro",
      "lastname": "Juárez",
      "default_shipping": true,
      "default_billing": true
    }
  ],
  "disable_auto_group_change": 0,
  "extension_attributes": {
    "openpay_card": [
      {
        "customer_id": "atxdxf8dtsfiqj6jlykp",
        "card_id": "krotlgka8xqeebtxpjf9",
        "created_at": "2016-01-04T16:18:32-06:00",
        "updated_at": null,
        "token": null,
        "device_session_id": null,
        "type": "debit",
        "brand": "visa",
        "card_number": "411111XXXXXX1111",
        "holder_name": "Pedro Juarez Ramirez",
        "expiration_year": "20",
        "expiration_month": "12",
        "allows_charges": false,
        "allows_payouts": true,
        "bank_name": "Banamex",
        "bank_code": "002",
        "address": {
          "city": "Querétaro",
          "country_code": "MX",
          "line1": "Av 5 de Febrero",
          "line2": "Roble 207",
          "line3": "col carrillo",
          "postal_code": "76900",
          "state": "Queretaro"
        }
      }
    ]
  },
  "custom_attributes": [
    {
      "attribute_code": "openpay_customer_id",
      "value": "atxdxfodtsfiqj4jlykp"
    }
  ]
}
```

Delete a card
--------------

In order to delete a card, you will need to suppress the card from your PUT call and the module will infere you are trying to delete that card. 

> **Note:** This module looks for all the cards sent as part of the `customers` `extension_attributes` and it compares them with the previous cards that were saved. If an existing card is not present in your request, the module will delete such a card.

Here you can find an example for deleting a card:

### `[PUT] /rest/v1/customers/me`

### Request

**Body:**

```
{
  "customer": {
    "id": 1,
    "email": "example@domain.com",
    "firstname": "Pedro",
    "lastname": "Juárez",
    "storeId": 1,
    "websiteId": 1,
    "addresses": [
      {
        "country_id": "MX",
        "street": [
          "Some address 23"
        ],
        "telephone": "5555555558",
        "postcode": "09823",
        "city": "México",
        "firstname": "Pedro",
        "lastname": "Juárez",
        "default_shipping": true,
        "default_billing": true
      }
    ],
    "extension_attributes": {
      "openpay_card": []
    },
    "custom_attributes": [
      {
        "attribute_code": "openpay_customer_id",
        "value": "atxdxfodtsfiqj4jlykp"
      }
    ]
  }
}
```
### Response

```
{
  "id": 1,
  "group_id": 1,
  "default_billing": "1",
  "default_shipping": "1",
  "created_at": "2015-12-15 00:44:20",
  "updated_at": "2016-01-04 22:19:24",
  "created_in": "Default Store View",
  "email": "example@domain.com",
  "firstname": "Pedro",
  "lastname": "Juárez",
  "store_id": 1,
  "website_id": 1,
  "addresses": [
    {
      "id": 1,
      "customer_id": 1,
      "region": {
        "region_code": null,
        "region": null,
        "region_id": 0
      },
      "region_id": 0,
      "country_id": "MX",
      "street": [
        "Some address 23"
      ],
      "telephone": "5555555558",
      "postcode": "09823",
      "city": "México",
      "firstname": "Pedro",
      "lastname": "Juárez",
      "default_shipping": true,
      "default_billing": true
    }
  ],
  "disable_auto_group_change": 0,
  "extension_attributes": {
    "openpay_card": []
  },
  "custom_attributes": [
    {
      "attribute_code": "openpay_customer_id",
      "value": "atxdxfodtsfiqj4jlykp"
    }
  ]
}
```

Obtain a customer cards
----------------------

In order to get all the cards of a given customer, you will need to make a [GET] request to bring the customer and the response will include all of the customer's cards inside the `extension_attributes`.


### `[GET] /rest/v1/customers/me`

### Response

```
{
  "id": 1,
  "group_id": 1,
  "default_billing": "1",
  "default_shipping": "1",
  "created_at": "2015-12-15 00:44:20",
  "updated_at": "2016-01-04 22:19:24",
  "created_in": "Default Store View",
  "email": "example@domain.com",
  "firstname": "Pedro",
  "lastname": "Juárez",
  "store_id": 1,
  "website_id": 1,
  "addresses": [
    {
      "id": 1,
      "customer_id": 1,
      "region": {
        "region_code": null,
        "region": null,
        "region_id": 0
      },
      "region_id": 0,
      "country_id": "MX",
      "street": [
        "Some address 23"
      ],
      "telephone": "5555555558",
      "postcode": "09823",
      "city": "México",
      "firstname": "Pedro",
      "lastname": "Juárez",
      "default_shipping": true,
      "default_billing": true
    }
  ],
  "disable_auto_group_change": 0,
  "extension_attributes": {
    "openpay_card": [
      {
        "customer_id": "atxdxf8dtsfiqj6jlykp",
        "card_id": "krotlgka8xqeebtxpjf9",
        "created_at": "2016-01-04T16:18:32-06:00",
        "updated_at": null,
        "token": null,
        "device_session_id": null,
        "type": "debit",
        "brand": "visa",
        "card_number": "411111XXXXXX1111",
        "holder_name": "Pedro Juarez Ramirez",
        "expiration_year": "20",
        "expiration_month": "12",
        "allows_charges": false,
        "allows_payouts": true,
        "bank_name": "Banamex",
        "bank_code": "002",
        "address": {
          "city": "Querétaro",
          "country_code": "MX",
          "line1": "Av 5 de Febrero",
          "line2": "Roble 207",
          "line3": "col carrillo",
          "postal_code": "76900",
          "state": "Queretaro"
        }
      }
    ]
  },
  "custom_attributes": [
    {
      "attribute_code": "openpay_customer_id",
      "value": "atxdxfodtsfiqj4jlykp"
    }
  ]
}
```
