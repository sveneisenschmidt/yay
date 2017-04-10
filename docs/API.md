# Achievements #

## /api/achievements/ ##

### `GET` /api/achievements/ ###

_Returns a collection of all known Achievements_

**Example Response:**
```json
[{
    "name": "yay.goal.demo_goal_1",
    "links": {
        "self": "http://example.org/api/achievements/yay.goal.demo_goal_1",
        "actions": ["http://example.org/api/actions/yay.action.demo_action"]
    }
}, {
    "name": "yay.goal.demo_goal_2",
    "links": {
        "self": "http://example.org/api/achievements/yay.goal.demo_goal_2",
        "actions": ["http://example.org/api/actions/yay.action.demo_action"]
    }
}]
```



## /api/achievements/{name} ##

### `GET` /api/achievements/{name} ###

_Returns an Achievement identified by its name property_

**Example Response:**
```json
{
    "name": "yay.goal.demo_goal_1",
    "links": {
        "self": "http://example.org/api/achievements/yay.goal.demo_goal_1",
        "actions": ["http://example.org/api/actions/yay.action.demo_action"]
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
        "self": "http://example.org/api/actions/yay.action.demo_action",
    }
}]
```



## /api/actions/{name} ##

### `GET` /api/actions/{name} ###

_Returns an Action identified by its name property_

**Example Response:**
```json
{
    "name": "yay.action.demo_action",
    "links": {
        "self": "http://example.org/api/actions/yay.action.demo_action",
    }
}
```


#### Requirements ####

**name**

- Requirement: [A-Za-z0-9\-\_\.]+
- Type: string



# Players #

## /api/players/ ##

### `GET` /api/players/ ###

_Returns a collection of all known Players_

**Example Response:**
```json
[{
    "name": "Toney Beatty",
    "username": "gschowalter",
    "links": {
        "self": "http://example.org/api/players/gschowalter",
        "personal_achievements": "http://example.org/api/players/gschowalter/personal-achievements",
        "personal_actions": "http://example.org/api/players/gschowalter/personal-actions"
    }
}]
```



## /api/players/{username} ##

### `GET` /api/players/{username} ###

_Returns a Player identified by its username property_

**Example Response:**
```json
{
    "name": "Toney Beatty",
    "username": "gschowalter",
    "links": {
        "self": "http://example.org/api/players/gschowalter",
        "personal_achievements": "http://example.org/api/players/gschowalter/personal-achievements",
        "personal_actions": "http://example.org/api/players/gschowalter/personal-actions"
    }
}
```


#### Requirements ####

**username**

- Requirement: [A-Za-z0-9\-\_\.]+
- Type: string


## /api/players/{username}/personal-achievements ##

### `GET` /api/players/{username}/personal-achievements ###

_Returns a Player achievements identified by its username property_

**Example Response:**
```json
[{
    "name": "yay.goal.demo_goal_1",
    "achieved_at": "2017-04-07T14:12:29+0000",
    "links": {
        "self": "http://example.org/api/players/gschowalter/personal-achievements",
        "player": "http://example.org/api/players/gschowalter",
        "achievement": "http://example.org/api/achievements/yay.goal.demo_goal_1"
    }
}, {
    "name": "yay.goal.demo_goal_2",
    "achieved_at": "2017-04-07T14:12:29+0000",
    "links": {
        "self": "http://example.org/api/players/gschowalter/personal-achievements",
        "player": "http://example.org/api/players/gschowalter",
        "achievement": "http://example.org/api/achievements/yay.goal.demo_goal_2"
    }
}]
```


#### Requirements ####

**username**

- Requirement: [A-Za-z0-9\-\_\.]+
- Type: string


## /api/players/{username}/personal-actions ##

### `GET` /api/players/{username}/personal-actions ###

_Returns a Player achievements identified by its username property_

**Example Response:**
```json
[{
    "name": "yay.action.demo_action",
    "achieved_at": "2017-04-07T14:12:29+0000",
    "links": {
        "self": "http://example.org/api/players/gschowalter/personal-actions",
        "player": "http://example.org/api/players/gschowalter",
        "action": "http://example.org/api/actions/yay.action.demo_action"
    }
}, {
    "name": "yay.action.demo_action",
    "achieved_at": "2017-04-07T14:12:29+0000",
    "links": {
        "self": "http://example.org/api/players/gschowalter/personal-actions",
        "player": "http://example.org/api/players/gschowalter",
        "action": "http://example.org/api/actions/yay.action.demo_action"
    }
}]
```


#### Requirements ####

**username**

- Requirement: [A-Za-z0-9\-\_\.]+
- Type: string



# Progress of an Player #

## /api/progress/ ##

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
    "name": "yay.goal.demo_goal_1",
    "achieved_at": "2017-04-07T14:12:29+0000",
    "links": {
        "self": "http://example.org/api/players/gschowalter/personal-achievements",
        "player": "http://example.org/api/players/gschowalter",
        "achievement": "http://example.org/api/achievements/yay.goal.demo_goal_1"
    }
}, {
    "name": "yay.goal.demo_goal_2",
    "achieved_at": "2017-04-07T14:12:29+0000",
    "links": {
        "self": "http://example.org/api/players/gschowalter/personal-achievements",
        "player": "http://example.org/api/players/gschowalter",
        "achievement": "http://example.org/api/achievements/yay.goal.demo_goal_2"
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
