# Sample symfony Rest API

This is a simple Rest API using symfony and PDO for database connection.

The rest api should now be reachable via https://local.be.asknicely.com/api/

*API is not production ready*

## Setup

This project runs using DDEV, below are the steps to get project running on Mac (similar steps for Windows/Linux, check the link below for reference).

[Get started](https://ddev.com/get-started/)

### Install Docker

Only needed if you don't already have docker in your environment.

```
brew install homebrew/cask/docker
```

### Docker Provider

You will also need to install a docker provider. There are a few options out there, but here are our top 3 options

-   [Colima](https://github.com/abiosoft/colima) - Recommended by ddev, but does not have GUI
-   [Docker Desktop App](https://www.docker.com/products/docker-desktop/)
-   [Orbstack](https://orbstack.dev/) - Alternative to Docker Desktop and claims to run lighter, faster and less cpu/hd hungry (Soon to become paid)

If using Colima, you can start docker with:
```
colima start --cpu 4 --memory 6 --disk 100 --vm-type=qemu --mount-type=sshfs --dns=1.1.1.1
```

### Install DDEV

```
brew install ddev/ddev/ddev
```

### Install mkcert (required for localhost SSL)

Only needed if you don't already have mkcert in your environment.

```
brew install mkcert nss
mkcert -install
```

* _Firefox will still flag custom https localhost domains as not having valid certificate_

### Start project

```
ddev start
```

_The first time you start your project or after machine restarts, you can be prompted to provide your password in order to add the custom domain to your hosts file._

Install composer dependencies

```
ddev composer install
```

Once the dependencies are installed you can start the symfony project with `ddev composer start`.

#### DB

This DDEV project has a mysql 10.4 database and can be opened with your preferred db management application.
If you have SequelAce installed ddev offer a quick command to open this project's database. `ddev sequelace`

#### Endpoints

Available endpoints
- Get All - GET /employess
- Get One - GET /employees/{ID}
- Create Employess - POST /employees
```
Request body example

"[{'ACME Corporation','John Doe','johndoe@acme.com',50000}, {...}, {...}]"
```
- Update Employee Email - PATCH /employees/{ID}
```
Request body example
{
    "Email": "johndoe@acme.com"
}
```

# Todo - Improvements

- [ ] Implement API authentication
- [ ] Use actual ORM to deal with DB and DataObjects
- [ ] Further implement tests with mock data and mock DB
- [ ] Improve error handling on API endpoints
- [ ] Create API swagger schema
- [ ] More secure CORS rules implementation for endpoints