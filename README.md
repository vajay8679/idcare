# IDCARE Project setup
    Idcare project setup with docker, shell scripts and makefile for easy development environment.

## Requirements
- Docker: Install docker engine or docker desktop based on your operating system. For installation instruction [follow](https://docs.docker.com/engine/install/)
- Docker compose: Install docker compose for managing multiple docker containers. For install instruction [follow](https://docs.docker.com/compose/install/)
- Make tool: Using make tool for short commands for starting, stopping and restarting docker containers. Installation [instructions](https://www.gnu.org/software/make/)

## Development Environment setup
- Clone git [repo](https://github.com/ProfitLion1234/IDCARE.git)
    ```git
    git clone https://github.com/ProfitLion1234/IDCARE.git
    ```
- Change directory to project folder
    ```shell
    cd IDCARE
    ```
- Inside project folder run 
    ```make 
    make start
    ```
    or 
    ```shell
    sh ./start.sh
    ```
- Check running docker containers
    ```
    docker ps
    ```
    Three docker containers should be running
    | Container name | Service name | Comment |
    |----------------|--------------|---------|
    | idcare-database|database      | Runs mysql server|
    |idcare-server|server|Runs php + apache server|
    |phpmyadmin|phpmyadmin|Runs phpmyadmin|

## Resources
- Visit <b>http://localhost:2020</b> for accessing phpmyadmin
- Use <b>http://localhost/api/v1</b> as base url for accessing API endpoints

### Note
- There is a <i>.env</i> file in project root that contains database env variables for docker setup
- In <b>BACKEND/application/config/database.php</b>, localhost settings uses docker service name i.e. database, and database username & password from the .env file
- database service imports the database placed in BACKEND/sql-files the first time database service is run.