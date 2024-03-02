# SeAT-Notifcations+

[![Latest Version on Packagist](https://img.shields.io/packagist/v/helious/seat-beacons.svg?style=flat-square)](https://packagist.org/packages/helious/seat-beacons)
[![Total Downloads](https://img.shields.io/packagist/dt/helious/seat-beacons.svg?style=flat-square)](https://packagist.org/packages/helious/seat-beacons)

Notifications+ plugin for [SeAT](https://github.com/eveseat/seat) and basically pulls notifications for characters of a specified corporation and sends them to a discord channel about about things such as SOV, Citadel Events (Anchoring, Under Attack, etc.). 

![https://i.imgur.com/UtfvJ0B.png](https://i.imgur.com/UtfvJ0B.png)

## Why not use SeAT's built in notifications?
because it only grabs notifications once every 20min(by default) which can be improved by changing to schedule time on it but default SeAT notifcations sends multiple webhook messages for the same notification if you have mulitple characters for that corp plugged into seat. the design of the embeds it sends look awful and not very informative. So I decided to make my own to fix these issues by saving the notification id to a table and checking if it exists before sending it to the webhook and fixing the embeds to be more informative and look cleaner.


## Installation

You can install the package via composer:

```bash
composer require helious/seat-notifications
```

## Notifications
1. 

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
