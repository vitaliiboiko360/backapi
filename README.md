# users   

users  

Solution uses template from this repo https://github.com/wiliamhw/Laravel-9-Docker-Template

The template includes

- Laravel 9
- PHP 8.1.6,
- nginx,
- redis,
- Postgres SQL 14.4

Also used:

For frontend Vue.js 3 is used. Vite for bundling  

--  
  
1. Implemented   
GET /api/token  
and   
POST  /api/users  
GET  /api/users  

2. Data generation / seeders implemented  
close to real-world input  

3. Add new user POST request - processes image according to TinyPNG API "cover" mode   
with required dimensions and format.    
Authorization token could be used to register one user only. And once issued, the token expires after 40 minutes.  

4. Frontend is using Vue.js 3 Composition API.  
There are Next and Previous page buttons to paginate results from backend.  
Users are shown by 6 entries per page.   
Intentionally, there's no client-side validation at Frontend part but validation performed at Backend part.  
All errors and successes are reported to the user in the UI.     
   
--  

TinyPNG API is used for image resizing.  

Comments On Solution:  
Solution only includes files that were created/edited and only files that directly provide backend solution.  
A lot of template boilerplate code and configuration files were excluded.  

On Code Quality:  
frontend Vue code is packed into minimum number of files, however you could check my other projects here on github, where my Vue code  is maintained much nicer. Assuming, main focus is on backend, but frontend is an important part of this solution too. Too much logic has been put in a little number of files. In production code we usually aim towards better decoupling and composition.  

For php Laravel specifically, there was an effort to keep Model and Controller concerns separated as much as possible. However due to the requirement that we have to return several different statuses from one request, those methods appeared to be overloaded with logic.
