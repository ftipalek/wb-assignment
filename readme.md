## WB assignment

### How to setup app

```
make composerInstall

make dockerComposeUp
```


### Using API service

There are 2 ways how to use this API service.

#### REST API

- `curl` requests to the:
  - `GET http://localhost:8090/api/devices`: list devices (output JSON formatted)
  - `POST http://localhost:8090/api/devices`: create device. Request example: 
    ```json 
    {
        "hostname": "",
        "operating_system": "",
        "owner_uuid": "",
        "type": ""
    }
    ```
  - GET `http://localhost:8090/api/owners`: list devices (output JSON formatted)
  - POST `http://localhost:8090/api/owners`: create owner. Request example:
    ```json 
    {
        "firstname": "",
        "lastname": ""
    }
    ```

#### Console Commands

```sh
cd /var/www/html/bin

php cliConsole.php owners:add

php cliConsole.php owners:list

php cliConsole.php devices:add

php cliConsole.php devices:list 
```


### Options to improve

- Extract hardcoded DB credentials from code
- Properly use MySQL database even for unit tests instead or using in-memory sqlite
- Audit logging when changing data
- Authorization and authentication of users
