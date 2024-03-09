# SeAT-Notifcations+

[![Latest Version on Packagist](https://img.shields.io/packagist/v/helious/seat-notifications-plus.svg?style=flat-square)](https://packagist.org/packages/helious/seat-notifications-plus)
[![Total Downloads](https://img.shields.io/packagist/dt/helious/seat-notifications-plus.svg?style=flat-square)](https://packagist.org/packages/helious/seat-notifications-plus)

Notifications+ plugin for [SeAT](https://github.com/eveseat/seat) and basically pulls notifications for characters of a specified corporation and sends them to a discord channel about about things such as SOV, Citadel Events (Anchoring, Under Attack, etc.). 

![https://i.imgur.com/NAvfjG9.jpeg](https://i.imgur.com/NAvfjG9.jpeg)

## Why not use SeAT's built in notifications?
default SeAT notifcations sends multiple  messages for the same notification if you have multiple characters for that corp under the affilicated corp as theres no checking when a last notifcation was sent or handling for more than one character it doesnt appear to be designed this way and the design of the embeds it sends are not very informative. So I decided to make my own to fix these issues by checking when a last notification was sent for that afflicated corp and fixing the embeds to be more informative and look cleaner (by reusing the old code from default notifcations i just refactored how the embeds are look and the info they display).


## Installation

You can install the package via composer:

```bash
SeAT 5: composer require helious/seat-notifications-plus
SeAT 4: composer require helious/seat-notifications-plus:4.*
```

or via docker
```bash
SeAT 5: SEAT_PLUGINS=helious/seat-notifications-plus
SeAT 4: SEAT_PLUGINS=helious/seat-notifications-plus:4.*
```
## Notifications
> :warning: **DISCORD ONLY SUPPORTED** FOR SeAT 5 

All the new notifcation types have `[N+]` appended so be sure to select the correct Alerts when setting it up.
1. setup your Affiliations be either characters or corporations
2. add your alerts (eg AllAnchoringMsg [N+], EntosisCaptureStarted [N+], etc)
3. select your Integration of the webhook you created.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
