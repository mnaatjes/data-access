# Data-Access Package

* **Author:** Michael Naatjes
* **Version:** 1.0.0
* **Date:** 08/10/25

# 1 Overview

## 1.1

## 1.2 Directory Structure
```
.
├── config/
├── resources/
│   ├── data/
│   └── docs/
|
├── src/
│   ├── DataAccess/
│   ├── HttpCore/
│   ├── MVCCore/
│   └── Utils/
│   └── Container.php
|
├── tests/
├── LICENSE
└── README.md
```

# 2 Configuration and Setup

## 2.1 Composer.json file

* **Create:** `~/data-access/composer.json`

```json
{
    "name": "mnaatjes/data-access",
    "description": "",
    "type": "library",
    "license": "MIT",
    "authors": [
        {
            "name": "Michael Naatjes",
            "email": "michael.naatjes87@gmail.com"
        }
    ],
    "autoload": {
        "psr-4": {
            "mnaatjes\\App\\": "src/"
        }
    },
    "require": {
        "php": "^8.1"
    }
}
```