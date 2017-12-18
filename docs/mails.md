# Mails #

Yay sends a set of emails when events occur. It is powered by [Lee Munroe's](https://github.com/leemunroe) amazing [email templates](https://github.com/leemunroe/responsive-html-email-template). 

It is possible to override the layout ([`templates/Mail/layout.html.twig`](../templates/Mail/layout.html.twig).) or any other template for your liking.

The following emails are send:

## New Player joined Yay!
```
Yay!

You've been awared a new achievement:

1x Code-aholic - Perform five times an action on any pull request. Can be awarded multiple times.

Have a nice day and keep on rockin'!
```
You can find the template in [`templates/Mail/create_player.html.twig`](../templates/Mail/create_player.html.twig). Sending is triggeret by the [`yay.engine.create_player`](../src/App/Mail/EventListener/MailListener.php) event.

## New personal action recorded
```
Yay!

A new action has been recorded for you:

1x Pull request opened - ...

Have a nice day and keep on rockin'!
```
You can find the template in [`templates/Mail/grant_personal_achievement.html.twig`](../templates/Mail/grant_personal_achievement.html.twig). Sending is triggeret by the [`yay.engine.grant_personal_achievement`](../src/App/Mail/EventListener/MailListener.php) event.

## New personal achievement awared
```
Yay!

You've been awared a new achievement:

1x Code-aholic - Perform five times an action on any pull request. Can be awarded multiple times.

Have a nice day and keep on rockin'!
```
You can find the template in [`templates/Mail/grant_personal_action.html.twig`](../templates/Mail/grant_personal_action.html.twig). Sending is triggeret by the [`yay.engine.grant_personal_action`](../src/App/Mail/EventListener/MailListener.php) event.