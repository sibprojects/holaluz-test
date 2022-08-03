# Holaluz test task.

At Holaluz we are worried about fraud in electricity readings and we have decided to implement a suspicious reading detector. 

Some clients have phoned us suspecting some squatters have been tapping into their electricity lines and this is why you may find some extremely high readings compared to their regular usage.
At the same time, we suspect some clients are tapping their building electricity lines and you may also find extremely low readings.

As we all know, many systems in Spain are a bit old fashioned and get some readings in XML and some others in CSV, so we need to be able to implement adaptors for both inputs.

For this first iteration, we will try to identify readings that are either higher or lower than the annual median ± 50%.

Please write a command line application that takes a file name as an argument (such as 2016-readings.xml or 2016-readings.csv) and outputs a table with the suspicious readings:

| Client     | Month   | Suspicious | Median   |
|------------|---------|------------|----------|
| clientid   | month   | reading    | median   |
| clientid   | month   | reading    | median   |
| clientid   | month   | reading    | median   |

You can assume there are no tricks in the XML and CSV files. Each client will have 12 readings and you get all 12 consecutively. Please don't spend time trying to validate all this although it happens in real life sometimes!

In this exercise, we are looking for things like:

   - Hexagonal architecture to handle different inputs (CSV and XML in this case, but it could be a database or even a txt file in a remote FTP! True story...)

 Bonus points if you use:
   - Idiomatic features of the language
   - Automated tests
   - Git
   - Docker or similar

The solution can be written in any of our stack languages: PHP, Python or Java.

You can use any external library or language version :)

---
## To testing result:

```
docker-compose -f ./docker/docker-compose.yml build
docker-compose -f ./docker/docker-compose.yml up -d --remove-orphans
docker-compose -f ./docker/docker-compose.yml exec -u www-data php-fpm bash
php bin/console app:read tests/data/2016-readings.csv
php bin/console app:read tests/data/2016-readings.xml 
php bin/phpunit
```
