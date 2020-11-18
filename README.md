# incident-api
// open the terminal/commend prompt navigate to the folder 
1. $ cd incident-api

//install dependencies
2. $ composer install

// copy the .env file from .env.example
3. cp .env.example .env

4. update the database information in .env  with the following

    APP_ENV: choose which mode want to run either local production
    
    DB_CONNECTION=mysql
    
	DB_HOST= host name
    
	DB_PORT= port number
    
	DB_DATABASE= DB Name
    
	DB_USERNAME= db username 
    
	DB_PASSWORD= db password
    

5. Migration :  with the following commend

    php artisan make:migrate --seed

6. Testing : 
    
    * Make sure sqlite connection will be there at Config/database.php

    * Root Directory -> phpunit.xml add the following in between <php>  Add here </php>

        <server name="DB_CONNECTION" value="sqlite"/>
        <server name="DB_DATABASE" value=":memory:"/> 

    * clear config cache : Use either one of the following
         php artisan cache:clear
         php artisan optimize  

    * Test with following command on terminial 
        Option 1: php artisan test 
        Option 2: composer test
        Option 3: ./vendor/bin/phpunit

7.  API End point access 

    Get Incident : {server url}/api/incident
    
    Post Incident: {server url}/api/incident
    

    Get Incident Sort by field , order by  : {server url}/api/incident?sort_by=title&order_by=desc
    
    Get Incident filter by field: {server url}/api/incident?title=test
    
    Pagination: {server url}/api/incident?page=2


8.  Test with Postman 

        get method: just enter url which is {server url}/api/incident

        Post method : just enter url which is {server url}/api/incident

          input format : Go to Body and select raw and copy the paste there and choose input as json
            {
                "location": {
                    "longitude": "1212",
                    "latitude" : "1122"
                },
                "title": "This is testing incident",
                "category": "1",
                "incidentDate":"19-11-2020 10:20:12",
                "createDate": "20-11-2020 12:10:12"
            }

     



