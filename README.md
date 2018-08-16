# TODO
* [x] Disable access logs in NGINX - Done 2018-08-16 11:33:09

# Thoughts

When we setup the Monzo webhook URL, could we give it an encryption_key for the YNAB refresh_token which we encrypt on disk:

### Flow:
* Request user's email if there are any issues (they don't add a transaction for 6 months and our `refresh_token` expires, but maybe we cronjob so that doesn't happen?)
* User OAuth's Monzo
* If they have more than 1 account (current, joint) we ask them which they want to use
* Once we know which account they want to sync we:
* * Generate an encryption key
* * Register a webhook: https://monzo-to-ynab.ashleyhindle.com/monzo/webhook/{encryption_key}
* User OAuth's YNAB
* User selects YNAB account to sync Monzo transactions to
* We encrypt the YNAB `refresh_token` with the encryption key generated earlier and store that in a database

This way if our database/code is stolen, we don't have the monzo refresh_token (no need for it), and we don't have an unencrypted YNAB token so an attacker can do literally nothing with the data we have.

What are the cons? 

---

We need to link Monzo accounts with YNAB `refresh_token`s and `account_id`s, so when the webhook comes through and passes the Monzo account_id, we can link it to the correct account in YNAB

Example Monzo webhook:

```json
{
    "type": "transaction.created",
    "data": {
        "account_id": "acc_00008gju41AHyfLUzBUk8A",
        "amount": -350,
        "created": "2015-09-04T14:28:40Z",
        "currency": "GBP",
        "description": "Ozone Coffee Roasters",
        "id": "tx_00008zjky19HyFLAzlUk7t",
        "category": "eating_out",
        "is_load": false,
        "settled": true,
        "merchant": {
            "address": {
                "address": "98 Southgate Road",
                "city": "London",
                "country": "GB",
                "latitude": 51.54151,
                "longitude": -0.08482400000002599,
                "postcode": "N1 3JD",
                "region": "Greater London"
            },
            "created": "2015-08-22T12:20:18Z",
            "group_id": "grp_00008zIcpbBOaAr7TTP3sv",
            "id": "merch_00008zIcpbAKe8shBxXUtl",
            "logo": "https://pbs.twimg.com/profile_images/527043602623389696/68_SgUWJ.jpeg",
            "emoji": "🍞",
            "name": "The De Beauvoir Deli Co.",
            "category": "eating_out"
        }
    }
}
```

YNAB post on how they transform a Monzo transaction to a YNAB: https://support.youneedabudget.com/t/k95bh0/monzo-to-ynab-ifttt-integration

Mainly:
```ecmascript 6
const account_id = "31656018-364d-FAKE-3bf5b119f3d3";
// Use current date in ISO format (i.e. '2018-06-02')
const date = Meta.currentUserTime.toISOString().substr(0, 10);

const account_amount = Monzo.cardPurchase.AmountInAccountCurrency;

// Convert account_amount to milliunits and use negative
// amount so it will be entered as an outflow
const amount = -Number(account_amount) * 1000;

const payee_name = Monzo.cardPurchase.MerchantName;
const memo = "I came from IFTTT!";

// Use an import_id consistent with other imported transactions
// to prevent duplicates when doing File-based import
const import_id = Monzo.cardPurchase.TransactionId;

let transaction = { account_id, date, amount, payee_name, memo, import_id };
let body = JSON.stringify({ transaction });
MakerWebhooks.makeWebRequest.setBody(body);
```

---

# Can I just use yours?

Sure - just go to https://monzo-to-ynab.ashleyhindle.com/

# Privacy

This system will only register a webhook, then send any new transactions to the specified YNAB account.

It will _not_:
- View, log, or store your balance
- View, log, or store your transactions
- View, log or store your pots
- View, log, or store your feed items
- View, log, or store attachments (receipts generally)

We request an email address to send any alerts to, such as the service being unable to use your Monzo or YNAB security tokens, so you can decide what to do.

# Self Hosted

### Steps

- Clone repo
- `composer install`
- Setup Monzo Client
- Setup YNAB
- Update .env
- Setup webserver pointing to Laravel app
- - `sudo apt-get install php7.2-mbstring php7.2-xml php7.2-curl`

### Monzo Setup

You'll need to setup a Monzo 'Client' here: https://developers.monzo.com/apps

Set the 'Redirect URL' `/monzo/redirect`
etc..


### YNAB Setup

Setup a YNAB 'OAuth Application': https://app.youneedabudget.com/settings/developer

Redirect URL: `/ynab/redirect`
--- TODO --- ^^^^
