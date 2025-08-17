

# Data-Access Package

## Project Template and Framework Configuration and Installation

* **Author:** Michael Naatjes
* **Version:** 1.0.0
* **Date:** 08/10/25

# 1 Overview

## 1.1 Library / Framework Directory Structure

This is the directory structure of the `mnaatjes/data-access/` package.
This is what ends up in `vendors/` directory when installed by composer.
```
.
│
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
│   └── ...replicate template environment.../
|
├── LICENSE
└── README.md
```

## 1.2 Project / Template Directory Structure
.
│
├── app/
│   ├── Controllers/
│   └── Middleware/
│
├── bootstrap/
│   ├── app.php
│   └── cache/
│
├── config/
│   ├── config.env
│   └── cache/
│
├── database/
│   ├── Models/
│   └── Repositories/
│
├── public/
│   └── index.php
│
├── resources/
│   ├── css/
│   ├── sass/
│   ├── js/
│   ├── lang/
│   └── views/
│
├── routes/
│   ├── web.php
│   ├── api.php
│   ├── console.php
│   └── channels.php
│
├── storage/
│   ├── app/
│   ├── framework/
│   └── logs/
│
├── tests/
│   
├── vendors/
|
├── .gitignore
└── README.md

This is the directory structure of the **Template** that the client will have after `composer create-project` is executed.


--- --- --- --- --- ---


# 2 Configuration and Setup


---
