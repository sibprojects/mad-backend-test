- Where to find your code 
  /app/config/
  - services.yaml - set path to source files
  /src/Entity/
  - Video.php - example of entity db
  /src/Command/
  - ImportCommand.php - console command
  /src/ImportVideo/
  - ImportVideo.php - parent class
  - ImportVideoFlub.php - flub parsing class
  - ImportVideoGlorf.php - glorf parsing class
  /src/texts/Unit/
  - ImportVideoTest.php - unit test for parsers

- Was it your first time writing a unit test, using a particular framework, etc? 
  - No, I have some experience with unit tests before on this framework

- What would you have done differently if you had had more time 
  - Ftp connection, add some progress bars, timeout checkers and more interface things :)

- Etc.
  - some used composer commands
    - composer update symfony/flex --no-plugins --no-scripts
    - composer update
    - composer require symfony/serializer-pack
    - composer require symfony/property-access
