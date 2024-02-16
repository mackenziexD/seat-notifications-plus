# SeAT-Beacons - Beacons Dashboard plugin for SeAT

[![Latest Version on Packagist](https://img.shields.io/packagist/v/helious/seat-beacons.svg?style=flat-square)](https://packagist.org/packages/helious/seat-beacons)
[![Total Downloads](https://img.shields.io/packagist/dt/helious/seat-beacons.svg?style=flat-square)](https://packagist.org/packages/helious/seat-beacons)

Beacons plugin for [SeAT](https://github.com/eveseat/seat) and was created out of necessity to see exactly how long until a beacon ran out of fuel so refuel ops could be coordinated around the times. 

![https://i.imgur.com/UtfvJ0B.png](https://i.imgur.com/UtfvJ0B.png)

## Installation

You can install the package via composer:

```bash
composer require helious/seat-beacons
```

## Permissions
- Remember to give Roles the `Access Beacons` role under Seat-beacons

## Notifications
1. Create A `Integrations` with the your Slack/Discord webhook.
2. Go To Sidebar > `Notifications` > `Notification Groups`
3. Create a Group Name.
4. Edit the Group we created.
5. Add Your Slack/Discord webhook .
6. Alerts To `seat_beacons_warnings`.
7. Add and your all set!.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
