# Demo #

To run the demo we need to enable the demo integration, you can do so by using the built-in make command `make demo-import` for your local dev environment or executing the appropriate console command (`php bin/console yay:integration:enable integration/demo`), we choose the former.

```bash
$ make enable-demo

[OK] Integration "default" enabled
[OK] Integration "demo" enabled
```

Now we can run the application, it will start the built-in PHP web-server. This is not recommended for produciton environments.
```bash
$ make start
```
_Output:_
```bash
yay_mysqldb_1 is up-to-date
yay_redis_1 is up-to-date
yay_web_1 is up-to-date
# http://localhost:50080
```

1\) Make a request to [localhost:50080/api/players](http://localhost:50080/api/players).
```bash
curl -X "GET" http://localhost:50080/api/players/

[]
```

There are no users created yet, let's create a user by executing the following request.
```bash
curl -X "POST" http://localhost:50080/api/players/ \
    -d "{\"name\": \"Jane Doe\",\"username\":\"jane.doe\",\"email\": \"jane.doe@example.org\",\"image_url\":\"https://api.adorable.io/avatars/128/354\"}"

{
    "name": "Jane Doe",
    "username": "jane.doe",
    "image_url": "https://api.adorable.io/avatars/128/354",
    "score": 0,
    "links": {
        "self": "http://localhost:50080/api/players/jane.doe/",
        "personal_achievements": "http://localhost:50080/api/players/jane.doe/personal-achievements/",
        "personal_actions": "http://localhost:50080/api/players/jane.doe/personal-actions/"
    }
}
```

2\) Make a request to [localhost:50080/api/actions](http://localhost:50080/api/actions). Here you can see a list of available actions a user can perform. Let us pick`demo-action`.
```bash
curl -X "GET" http://localhost:50080/api/actions/

[
    {
        "name": "demo-action",
        "label": "Accusamus molestias eum libero ullam libero.",
        "links": {
            "self": "http://localhost:50080/api/actions/demo-action/"
        }
    }
]
```

3\) Make a request to [localhost:50080/api/achievements](http://localhost:50080/api/achievements).
Here you can see a list of available achievements, in this demo you can earn both achievements by
performing 5x the action `demo-action` for the first achievement and then 10x the
action `demo-action` for the second achievement.

```bash
curl -X "GET" http://localhost:50080/api/achievements/

[
    {
        "name": "demo-achievement-01",
        "label": "Facere quibusdam iure voluptas velit sapiente.",
        "points": 50,
        "links": {
            "self": "http://localhost:50080/api/achievements/demo-achievement-01/",
            "actions": [
                "http://localhost:50080/api/actions/demo-action/"
            ]
        }
    },
    {
        "name": "demo-achievement-02",
        "label": "Nulla soluta iusto recusandae est veritatis nesciunt sequi.",
        "points": 50,
        "links": {
            "self": "http://localhost:50080/api/achievements/demo-achievement-02/",
            "actions": [
                "http://localhost:50080/api/actions/demo-action/"
            ]
        }
    }
]
```

4\) Let our player `jane.doe` perform the action `demo-action` one time.
```bash
curl -X "POST" http://localhost:50080/api/progress/ \
    -d "{\"username\":\"jane.doe\",\"action\":\"demo-action\"}"

[]
```

No achievment were granted yet, although if you check now our players personal actions you will find our recent action.
```bash
curl -X "GET" http://localhost:50080/api/players/jane.doe/personal-actions

[
    {
        "name": "demo-action",
        "label": "demo-action",
        "description": "demo-action",
        "achieved_at": "2017-10-08T12:48:13+0000",
        "links": {
            "self": "http://localhost:50080/api/players/jane.doe/personal-actions/",
            "player": "http://localhost:50080/api/players/jane.doe/",
            "action": "http://localhost:50080/api/actions/demo-action/"
        }
    }
]
```

Now let us get our first achievement. For this, perform four more times our `demo.action` action.
```bash
curl -X "POST" http://localhost:50080/api/progress/ \
    -d "{\"username\":\"jane.doe\",\"actions\":[\"demo-action\",\"demo-action\",\"demo-action\",\"demo-action\"]}"

[
    {
        "name": "demo-achievement-01",
        "label": "demo-achievement-01",
        "description": "demo-achievement-01",
        "points": 50,
        "achieved_at": "2017-10-08T13:22:08+0000",
        "links": {
            "self": "http://localhost:50080/api/players/jane.doe/personal-achievements/",
            "player": "http://localhost:50080/api/players/jane.doe/",
            "achievement": "http://localhost:50080/api/achievements/demo-achievement-01/"
        }
    }
]
```
Et voilà, we earned our first achievement!

5\) Now let us go further, Let the player perform five times more the `demo-action` action.
```bash
curl -X "POST" http://localhost:50080/api/progress/ \
    -d "{\"username\":\"jane.doe\",\"actions\":[\"demo-action\",\"demo-action\",\"demo-action\",\"demo-action\",\"demo-action\",\"demo-action\"]}"

[
    {
        "name": "demo-achievement-02",
        "label": "demo-achievement-02",
        "description": "demo-achievement-02",
        "points": 50,
        "achieved_at": "2017-10-08T13:23:53+0000",
        "links": {
            "self": "http://localhost:50080/api/players/jane.doe/personal-achievements/",
            "player": "http://localhost:50080/api/players/jane.doe/",
            "achievement": "http://localhost:50080/api/achievements/demo-achievement-02/"
        }
    }
]⏎
```

Clap, clap! Our player earned now his official second achievemennt.


6\) Let's check now our player achievements by performing the following request.
```bash
curl -X "GET" http://localhost:50080/api/players/jane.doe/personal-achievements

[
    {
        "name": "demo-achievement-01",
        "label": "demo-achievement-01",
        "description": "demo-achievement-01",
        "points": 50,
        "achieved_at": "2017-10-08T13:22:08+0000",
        "links": {
            "self": "http://localhost:50080/api/players/jane.doe/personal-achievements/",
            "player": "http://localhost:50080/api/players/jane.doe/",
            "achievement": "http://localhost:50080/api/achievements/demo-achievement-01/"
        }
    },
    {
        "name": "demo-achievement-02",
        "label": "demo-achievement-02",
        "description": "demo-achievement-02",
        "points": 50,
        "achieved_at": "2017-10-08T13:23:53+0000",
        "links": {
            "self": "http://localhost:50080/api/players/jane.doe/personal-achievements/",
            "player": "http://localhost:50080/api/players/jane.doe/",
            "achievement": "http://localhost:50080/api/achievements/demo-achievement-02/"
        }
    }
]
```

And that's all for the demo. Yay!
