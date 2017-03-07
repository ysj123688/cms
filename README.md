建立一个cms系统，基于前面的restful API项目
使用PHP+mysql+redis



DIRECTORY STRUCTURE
-------------------

```
api
    common/              contains common
    config/              contains api configurations
    modules/             contains api versions separated by modules
        v1/              contains a major api version
            controllers/ contains version specific api controllers
            models/      contains version specific api models
    runtime/             contains files generated during runtime
    web/                 contains the entry script
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
panel
    assets/              contains application assets such as JavaScript and CSS
    config/              contains panel configurations
    controllers/         contains Web controller classes
    models/              contains panel-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for panel application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
```
