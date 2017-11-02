# Achievements #

## /api/achievements/ ##

### `GET` /api/achievements/ ###

_Returns a collection of all known Achievements_

**Example Response:**
```json
[{
    "name": "demo-achievement-01",
    "links": {
        "self": "http://example.org/api/achievements/demo-achievement-01/",
        "actions": ["http://example.org/api/actions/demo-action/"]
    }
}, {
    "name": "demo-achievement-01",
    "links": {
        "self": "http://example.org/api/achievements/demo-achievement-01/",
        "actions": ["http://example.org/api/actions/demo-action/"]
    }
}]
```



## /api/achievements/{name}/ ##

### `GET` /api/achievements/{name}/ ###

_Returns an Achievement identified by its name property_

**Example Response:**
```json
{
    "name": "demo-achievement-01",
    "links": {
        "self": "http://example.org/api/achievements/demo-achievement-01/",
        "actions": ["http://example.org/api/actions/demo-action/"]
    }
}
```


#### Requirements ####

**name**

- Requirement: [A-Za-z0-9\-\_\.]+
- Type: string



# Actions #

## /api/actions/ ##

### `GET` /api/actions/ ###

_Returns a collection of all known Actions_

**Example Response:**
```json
[{
    "name": "yay.action.demo_action",
    "links": {
        "self": "http://example.org/api/actions/yay.action.demo_action/",
    }
}]
```



## /api/actions/{name}/ ##

### `GET` /api/actions/{name}/ ###

_Returns an Action identified by its name property_

**Example Response:**
```json
{
    "name": "yay.action.demo_action",
    "links": {
        "self": "http://example.org/api/actions/yay.action.demo_action/",
    }
}
```


#### Requirements ####

**name**

- Requirement: [A-Za-z0-9\-\_\.]+
- Type: string



# Levels #

## /api/levels/ ##

### `GET` /api/levels/ ###

_Returns a collection of all known Levels_

**Example Response:**
```json
[{
    "name": "Level 1",
    "label": "label-001",
    "description": "description-001",
    "level": 1,
    "points": 100
},{
    "name": "Level 2",
    "label": "label-002",
    "description": "description-002",
    "level": 2,
    "points": 200
}]
```




# Misc #

## /api/leaderboard/ ##

### `GET` /api/leaderboard/ ###

_Returns a sorted collection of all Players that have more than 0 points, starting with the highest score._

**Example Response:**
```json
[{
    "name": "Toney Beatty",
    "username": "gschowalter",
    "points": 200,
    "links": {
        "self": "http://example.org/api/players/gschowalter/",
        "personal_achievements": "http://example.org/api/players/gschowalter/personal-achievements/",
        "personal_actions": "http://example.org/api/players/gschowalter/personal-actions/"
    }
},{
    "name": "Carmen Davis",
    "username": "cdavis",
    "points": 125,
    "links": {
        "self": "http://example.org/api/players/cdavis",
        "personal_achievements": "http://example.org/api/players/cdavis/personal-achievements/",
        "personal_actions": "http://example.org/api/players/cdavis/personal-actions/"
    }
}]
```




# Players #

## /api/players/ ##

### `GET` /api/players/ ###

_Returns a collection of all known Players_

**Example Response:**
```json
[{
    "name": "Jane Doe",
    "username": "jane.doe",
    "links": {
        "self": "https://example.org/api/players/jane.doe/",
        "personal_achievements": "https://example.org/api/players/jane.doe/personal-achievements/",
        "personal_actions": "https://example.org/api/players/jane.doe/personal-actions/"
    }
}]
```



### `POST` /api/players/ ###

_Creates a new player_

**Example Request:**
```json
{
    "name": "Billy Turner V",
    "username": "marianne58",
    "email": "marianne58@gmail.com",
    "image_url": "https://api.adorable.io/avatars/128/497"
}
```

**Example Response:**
```json
{
    "name": "Billy Turner V",
    "username": "marianne58",
    "image_url": "https://api.adorable.io/avatars/128/497",
    "score": 0,
    "links": {
        "self": "https://example.org/api/players/marianne58",
        "personal_achievements": "https://example.org/api/players/marianne58/personal-achievements/",
        "personal_actions": "https://example.org/api/players/marianne58/personal-actions/"
    }
}
```



## /api/players/{username}/ ##

### `GET` /api/players/{username}/ ###

_Returns a Player identified by its username property_

**Example Response:**
```json
{
    "name": "Jane Doe",
    "username": "jane.doe",
    "links": {
        "self": "https://example.org/api/players/jane.doe/",
        "personal_achievements": "https://example.org/api/players/jane.doe/personal-achievements/",
        "personal_actions": "https://example.org/api/players/jane.doe/personal-actions/"
    }
}
```


#### Requirements ####

**username**

- Requirement: [A-Za-z0-9\-\_\.]+
- Type: string


## /api/players/{username}/personal-achievements/ ##

### `GET` /api/players/{username}/personal-achievements/ ###

_Returns a Player achievements identified by its username property_

**Example Response:**
```json
[{
    "name": "demo-achievement-01",
    "achieved_at": "2017-04-07T14:12:29+0000",
    "links": {
        "self": "https://example.org/api/players/jane.doe/personal-achievements/",
        "player": "https://example.org/api/players/jane.doe/",
        "achievement": "https://example.org/api/achievements/demo-achievement-01/"
    }
}, {
    "name": "demo-achievement-02",
    "achieved_at": "2017-04-07T14:12:29+0000",
    "links": {
        "self": "https://example.org/api/players/jane.doe/personal-achievements/",
        "player": "https://example.org/api/players/jane.doe/",
        "achievement": "https://example.org/api/achievements/demo-achievement-02/"
    }
}]
```


#### Requirements ####

**username**

- Requirement: [A-Za-z0-9\-\_\.]+
- Type: string


## /api/players/{username}/personal-actions/ ##

### `GET` /api/players/{username}/personal-actions/ ###

_Returns a Player achievements identified by its username property_

**Example Response:**
```json
[{
    "name": "yay.action.demo_action",
    "achieved_at": "2017-04-07T14:12:29+0000",
    "links": {
        "self": "https://example.org/api/players/jane.doe/personal-actions/",
        "player": "https://example.org/api/players/jane.doe/",
        "action": "https://example.org/api/actions/yay.action.demo_action/"
    }
}, {
    "name": "yay.action.demo_action",
    "achieved_at": "2017-04-07T14:12:29+0000",
    "links": {
        "self": "https://example.org/api/players/jane.doe/personal-actions/",
        "player": "https://example.org/api/players/jane.doe/",
        "action": "https://example.org/api/actions/yay.action.demo_action/"
    }
}]
```


#### Requirements ####

**username**

- Requirement: [A-Za-z0-9\-\_\.]+
- Type: string



# Progress of an Player #

## /api/progress/ ##

### `GET` /api/progress/ ###

_Submit a payload to update a users progress_

**Example Request (1):**
```query
player=jane.doe&action=yay.action.demo_action
```

**Example Request (2):**
```query
player=jane.doe&actions[]=yay.action.demo_action&actions[]=yay.action.demo_action&actions[]=yay.action.demo_action&actions[]=yay.action.demo_action
```

**Example Response:**
```json
[{
    "name": "demo-achievement-01",
    "achieved_at": "2017-04-07T14:12:29+0000",
    "links": {
        "self": "http://example.org/api/players/gschowalter/personal-achievements/",
        "player": "http://example.org/api/players/gschowalter/",
        "achievement": "http://example.org/api/achievements/demo-achievement-01/"
    }
}, {
    "name": "demo-achievement-02",
    "achieved_at": "2017-04-07T14:12:29+0000",
    "links": {
        "self": "http://example.org/api/players/gschowalter/personal-achievements/",
        "player": "http://example.org/api/players/gschowalter/",
        "achievement": "http://example.org/api/achievements/demo-achievement-02/"
    }
}]
```


#### Requirements ####

**player**

- Requirement: [a-z\.\-\_]+
- Type: string
- Description: Username of the Player to progress

**action**

- Requirement: [a-z\.\-\_]+
- Type: string
- Description: Action that the Player has made progress in

**actions**

- Requirement: Array<[a-z\.\-\_]+>
- Type: array
- Description: Actions that the Player has made progress in


### `POST` /api/progress/ ###

_Submit a payload to update a users progress_

**Example Request (1):**
```json
{
    "player": "jane.doe",
    "action": "yay.action.demo_action"
}
```

**Example Request (2):**
```json
{
    "player": "jane.doe",
    "actions": [
        "yay.action.demo_action",
        "yay.action.demo_action",
        "yay.action.demo_action",
        "yay.action.demo_action"
    ]
}
```

**Example Response:**
```json
[{
    "name": "demo-achievement-01",
    "achieved_at": "2017-04-07T14:12:29+0000",
    "links": {
        "self": "http://example.org/api/players/gschowalter/personal-achievements/",
        "player": "http://example.org/api/players/gschowalter/",
        "achievement": "http://example.org/api/achievements/demo-achievement-01/"
    }
}, {
    "name": "demo-achievement-02",
    "achieved_at": "2017-04-07T14:12:29+0000",
    "links": {
        "self": "http://example.org/api/players/gschowalter/personal-achievements/",
        "player": "http://example.org/api/players/gschowalter/",
        "achievement": "http://example.org/api/achievements/demo-achievement-02/"
    }
}]
```


#### Requirements ####

**player**

- Requirement: [a-z\.\-\_]+
- Type: string
- Description: Username of the Player to progress

**action**

- Requirement: [a-z\.\-\_]+
- Type: string
- Description: Action that the Player has made progress in

**actions**

- Requirement: Array<[a-z\.\-\_]+>
- Type: array
- Description: Actions that the Player has made progress in
