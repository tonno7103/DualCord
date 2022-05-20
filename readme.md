# DualCord

#### DualCord is a Discord like application, which allows you to create chats with other people and send messages to them.

Installation requires:

- **[Node.js 16](https://nodejs.dev/download)**
- **MySQL**
- **Memcached**
- **[PHP 8.0](https://www.php.net/downloads.php)**
- **[Composer](https://getcomposer.org/download/)**
- **[Pusher API account](https://pusher.com)**


The repository already contains all downloaded modules the .env due to API pusher issues is not public.

### Installation

First, after installing MySQL, Memcached and PHP, you need to configure the database.
1) Edit the .env.example and change it to .env
2) Set up the .env file with the following values:
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dbname
DB_USERNAME=yourusername
DB_PASSWORD=yourpassword

BROADCAST_DRIVER=pusher
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=5000

PUSHER_APP_ID=yourappid
PUSHER_APP_KEY=yourkey
PUSHER_APP_SECRET=yoursecret
PUSHER_APP_CLUSTER=yourregion

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```
2) Change discordV3/utils/database.json to your database credentials.
4) Move to the PhpSide directory
5) Create the empty database with the `DB_DATABASE` used on the .env file.
6) Run the following command:
```
composer install
```
5) run the following command to create the database:
```bash
$ php artisan migrate
```
5) move back to the root directory
6) Start memcached with the following command:
```bash
$ memcached -d
```
6) Change on the discordV3/utils/path.json and PhpSide/storage/configs.json the address of the site and the port if you are using a localhost set the following address: `http://localhost:`
7) Now you can run the following command to start the application you must use two different terminals.
```bash
$ ./nodeStart.bat
$ ./startPhp.bat
```


### API

The API is designed to make the application work only with a physical browser. However, some routes can also be handled by normal requests.

Methods:

1) **POST** `l/user/have-access/guild/{guildId}`  - Check if the user has access to the guild. Returns a JSON with the result. 
<br> Body: `{ "user_id": "id" }`
2) **POST** `l/user/have-access/voice/{voice_id}`  - Check if the user has access to the VoiceChannel. Returns a JSON with the result.
<br> Body: `{ "user_id": "id" }`
3) **POST** `l/guild/getLevel/{guild_id}`  - Get the user level in the guild. Returns a JSON with the result.
<br> Body: `{ "user_id": "id" }`
4) **GET** `l/user/{user_id}` - Get the user information. Returns a JSON with the result.

    