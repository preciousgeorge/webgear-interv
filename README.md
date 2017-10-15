# Vouchers API

The Vouchers API is in the `vapi` folder

## Install the APi Application

Clone the project

    git clone https://github.com/preciousgeorge/webgear-interv

    cd webgear-interv/vapi

    composer install

## Run Application

    run php -S localhost:8080 -t public

* If all goes well the api should be running at port 8080 or which ever port you choose

# Vouchers Client

The Client is in `webgears-p` folder

## Install the Client Application

If you have already clone the project just chnage directory into webgears-p
   
    cd webgear-interv/webgears-p

    composer install

### setup database
    cd src

    open settings.php and set up database values

    import the sql file


## Run Application

    run php -S localhost:8090 -t public

* If all goes well the client should be running at port 8090 or which ever port you choose


