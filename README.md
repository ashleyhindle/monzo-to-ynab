This system isn't maintained - please use https://syncforynab.com/


----


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

# Local development setup

* `vagrant up`

#### URLs

* Main site: http://monzo-to-ynab.local
* Horizon (Redis queue manager): http://monzo-to-ynab.local/horizon

# TODO

- Request email address to notify if we fail to sync (OAuth token failure/revocation)
- Update README with self setup instructions
