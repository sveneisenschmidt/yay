trivago/yay
===

Gamification done simple.

## Demo

Import demo data and run the application
```bash
make import-demo
make run
```

1\) Make a request to [localhost:50080/api/players](http://localhost:50080/api/players). 
You can pick the username of the first player and use it for the upcoming requests
to trigger some actions we later want to reward. Let us assume you've picked `jane.doe`.

```bash
curl -X "GET" http://localhost:50080/api/players/
```

2\) Make a request to [localhost:50080/api/actions](http://localhost:50080/api/actions).
Here you can see a list of available actions a user can perform.
Let us assume you've picked `yay.action.demo_action`.

```bash
curl -X "GET" http://localhost:50080/api/actions/
```

3\) Make a request to [localhost:50080/api/achievements](http://localhost:50080/api/achievements).
Here you can see a list of available achievements, in this demo you can earn both achievements by 
performing 5x the action `yay.action.demo_action` for the first achievement and then 10x the
action `yay.action.demo_action` for the second achievement.

```bash
curl -X "GET" http://localhost:50080/api/achievements/
```

4\)  Let the player do progress 1x with `yay.action.demo_action`.
```bash
curl -X "POST" http://localhost:50080/api/progress/ \
    -d "{\"player\":\"jane.doe\",\"action\":\"yay.action.demo_action\"}"
```

**Request body**
```json
{
	"player": "jane.doe",
	"action": "yay.action.demo_action"
}
```

**Response body**
``json
[
    {
        "name": "yay.goal.demo_achievement_1",
        "achieved_at": "2017-04-10T18:14:23+0000",
        "links": {
            "self": "http://localhost:50080/api/players/jane.doe/personal-achievements",
            "player": "http://localhost:50080/api/players/jane.doe",
            "achievement": "http://localhost:50080/api/achievements/yay.goal.demo_achievement_1"
        }
    }
]⏎
``

5\) Let the player do progress 5x with `yay.action.demo_action`.
```bash
curl -X "POST" http://localhost:50080/api/progress/ \
    -d "{\"player\":\"jane.doe\",\"actions\":[\"yay.action.demo_action\",\"yay.action.demo_action\",\"yay.action.demo_action\",\"yay.action.demo_action\",\"yay.action.demo_action\"]}"
```

**Request body**
```json
{
	"player": "jane.doe",
	"actions": [
		"yay.action.demo_action",
		"yay.action.demo_action",
		"yay.action.demo_action",
		"yay.action.demo_action",
		"yay.action.demo_action"
	]
}
```

**Response body**
```json
[
    {
        "name": "yay.goal.demo_achievement_2",
        "achieved_at": "2017-04-10T18:19:31+0000",
        "links": {
            "self": "http://localhost:50080/api/players/jane.doe/personal-achievements",
            "player": "http://localhost:50080/api/players/jane.doe",
            "achievement": "http://localhost:50080/api/achievements/yay.goal.demo_achievement_2"
        }
    }
]⏎
```
The player should now have earned the `yay.goal.demo_achievement_1` achievement. By repearing step **5)** you can earn
the `yay.goal.demo_achievement_2` as well.


