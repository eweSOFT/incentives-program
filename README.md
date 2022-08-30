# Incentives Program
* The program is written with PHP symfony framework
* The main controller is in the controller folder with the name src/controller/IncentivesProgramController.php
* The route for the controller is configured inside the controller file with the endpoint - /calculate-balance, 
it accept get request from browser or post from postman if the line of code is activated.
* The data can be tweak as desired, it is a json string stored in src/Data/JsonDate.php
* The app is API ready. It can accept post request from POSTMAN by uncommenting the necessary codeat the begining of the main controller. 
* I de-activated the API due to the instruction that no DB, No API, No UI.
<img width="626" alt="Screenshot_98" src="https://user-images.githubusercontent.com/8293802/187547746-341f24a7-33dc-4bf8-81f8-8b8de4fd4e44.png">



# Assumptions:
* I assumed that, take for instance, if a user can earn a booster of 5points when an action is carried out within 5hours, then, the user can earn the same 5points
if the action is carried out in less than 5hours.
