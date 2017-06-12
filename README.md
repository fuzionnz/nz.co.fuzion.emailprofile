# Email in Profile

This extension includes email fields in profiles on the contribution page. The standard billing email address field is removed when an email field is found in profile.

Related SE question - https://civicrm.stackexchange.com/questions/16660/how-can-i-prevent-contribution-pages-forcing-the-email-to-be-of-type-billing

## Author

This extension was written by Dave Reedy on behalf of Fuzion Aotearoa.

## Configuration

No special configuration is required for this extension. Just install it!

## How it works

- If the contact is logged in - this field defaults to the 'Billing' email, if set, otherwise it is set to 'primary'.
- When the contribution is confirmed the email address is saved/updated on the contact as the 'Billing' email.

### Contribute

- Issue Tracker: https://github.com/fuzionnz/nz.co.fuzion.emailprofile/issues
- Source Code: https://github.com/fuzionnz/nz.co.fuzion.emailprofile

## Support

This extension is contributed by [Fuzion Aotearoa](https://www.fuzion.co.nz). Contact us for professional support and development requests.

We welcome contributions and bug reports via the extension's Github issue queue.

Community support is available via CiviCRM community channels:

* [CiviCRM chat](https://chat.civicrm.org)
* [CiviCRM question & answer forum on StackExchange](https://civicrm.stackexchange.com)
