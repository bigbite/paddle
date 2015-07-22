# Paddle

> A modified [Ship](ship.getherbert.com).


## Installation

If you're deploying with Peggy, it's really simple. Add it to Peggy, setup the [Environment](#environment), press Deploy.

Otherwise:
 * `git clone git@github.com:bigbitecreative/pebble.git`
 * Setup the [Environment](#environment)

On each deploy there-after:
 * `composer install`
 * `php artisan cache:clear`
 * `php artisan migrate --force`
 * `php artisan optimize`
 * `sudo supervisorctl reload`


## Worker

You're going to need to setup a supervisor worker:
```ini
[program:paddle-queue-listener]
user=root
command=php /path/to/paddle/artisan queue:listen --tries=3 --sleep=3 --timeout=60
directory=/
stdout_logfile=/var/log/worker-paddle-queue-listener.log
redirect_stderr=true
autostart=true
autorestart=true
process_name=%(program_name)s_%(process_num)s
numprocs=5
numprocs_start=0
```


## Cron

You're going to need to setup a cron job:
```sh
* * * * * php /path/to/paddle/artisan schedule:run 1>> /dev/null 2>&1
```


## Environment

```ini
APP_ENV=production
APP_DEBUG=false
APP_KEY=32 Character Random String

DB_HOST=...
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=...

MAIL_DRIVER=...
MAIL_HOST=...
MAIL_PORT=...
MAIL_FROM_ADDRESS=admin@paddle.app
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_ENCRYPTION=null

WEBHOOK_SECRET=Random String
SVN_BINARY=/usr/bin/svn

GITHUB_CLIENT_ID=...
GITHUB_CLIENT_SECRET=...

SSH_KEY_PATH=null

GIT_BRANCH=master
```

The environment follows basica Laravel environment rules (check Laravel's documentation for more information). For instance, if you want to use Mandrill for mail you can use these keys:
```ini
MAIL_DRIVER=mandrill
MANDRILL_SECRET=...
```

If you wish to use Iron pull queues, then use theses keys:
```ini
QUEUE_DRIVER=iron

IRON_HOST=...
IRON_TOKEN=...
IRON_PROJECT=...
IRON_QUEUE=...
```

If you wish to use Beanstalkd:
```ini
QUEUE_DRIVER=beanstalkd
```

etc.
