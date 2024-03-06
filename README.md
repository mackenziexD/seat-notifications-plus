# SeAT-Notifcations+

[![Latest Version on Packagist](https://img.shields.io/packagist/v/helious/seat-notifications-plus.svg?style=flat-square)](https://packagist.org/packages/helious/seat-notifications-plus)
[![Total Downloads](https://img.shields.io/packagist/dt/helious/seat-notifications-plus.svg?style=flat-square)](https://packagist.org/packages/helious/seat-notifications-plus)

Notifications+ plugin for [SeAT](https://github.com/eveseat/seat) and basically pulls notifications for characters of a specified corporation and sends them to a discord channel about about things such as SOV, Citadel Events (Anchoring, Under Attack, etc.). 

![https://i.imgur.com/NAvfjG9.jpeg](https://i.imgur.com/NAvfjG9.jpeg)

## Why not use SeAT's built in notifications?
because it only grabs notifications once every 20min(by default) which can be improved by changing to schedule time on it but default SeAT notifcations sends multiple webhook messages for the same notification if you have multiple characters for that corp plugged into seat. the design of the embeds it sends look awful and not very informative. So I decided to make my own to fix these issues by checking last the last notification was sent if it exists before sending it to the webhook and fixing the embeds to be more informative and look cleaner.


## Installation

You can install the package via composer:

```bash
SeAT 5: composer require helious/seat-notifications
SeAT 4: composer require helious/seat-notifications:v4.x-dev
```

or via docker
```bash
SeAT 5: SEAT_PLUGINS=helious/seat-notifications
SeAT 4: SEAT_PLUGINS=helious/seat-notifications:4.x-dev
```
## Notifications
> :warning: **DISCORD ONLY SUPPORTED** FOR SeAT 5 

All the new notifcation types have `[N+]` appended so be sure to select the correct Alerts when setting it up.
1. setup your Affiliations be either characters or corporations
2. add your alerts (eg AllAnchoringMsg [N+], EntosisCaptureStarted [N+], etc)
3. select your Integration of the webhook you created.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
