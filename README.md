# SeAT-Notifcations - notifcation to discord relay plugin for SeAT

[![Latest Version on Packagist](https://img.shields.io/packagist/v/helious/seat-beacons.svg?style=flat-square)](https://packagist.org/packages/helious/seat-beacons)
[![Total Downloads](https://img.shields.io/packagist/dt/helious/seat-beacons.svg?style=flat-square)](https://packagist.org/packages/helious/seat-beacons)

Notifications plugin for [SeAT](https://github.com/eveseat/seat) and basically employs that pulls notifications for characters of a specified corporation and sends them to a discord channel about about things such as SOV, Citadel Events (Anchoring, Under Attack, etc.). 

![https://i.imgur.com/UtfvJ0B.png](https://i.imgur.com/UtfvJ0B.png)

## Why not use SeATS built in notifications?
because it only grabs notifications once every 20min and sends multiple webhook messages for the same notification. This plugin will grab notifications every 2min (if you change the schedule) and only send one webhook message per notification.


## Installation

You can install the package via composer:

```bash
composer require helious/seat-notifications
```

## Permissions
- Remember to give Roles the `Access Notifications` role under Seat-beacons

## Notifications
1. 

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
