<?php

use App\Helpers;
use App\Logic\Route;

/*
 * ROUTES FOR PORTAL
 */



// Start routes
Route::get('/', 'PortalController@index');
Route::get('/index.php', 'PortalController@index');

// Route for get all cars for portal by GET method in JSON format
Route::post('/getcarsportal', 'CarsController@index');

// Route for user contact agency by POST method
Route::post('/contactagency', 'MessagesController@store');

// Route for rent car by POST method
Route::post('/rentcar', 'RentCarsController@store');

// Route for add user rating by POST method
Route::post('/addrating', 'RatingsController@store');

// Route for get text of about us by POST method in JSON format
Route::post('/getinfo', 'InformationsController@index');

// Route for get all ratings in JSON format
Route::post('/getratings', 'RatingsController@allowedRatings');

// Route for get contact information by POST method in JSON format
Route::post('/getconinfo', 'ContactInfoController@index');

// Route for add activity most popular car
Route::post('/addcarreview', function() {
    Helpers::storeReviewCar();
});

/*
 * END ROUTES FOR PORTAL
 */




/*
 * ROUTES OF PANEL
 */


// Route for admin panel
Route::get('/adminpanel', 'AdminController@index');

// Route for moderation panel
Route::get('/moderatorpanel', 'ModeratorController@index');

// Route for moderation panel
Route::get('/userpanel', 'UserController@index');

// Route for Login page
Route::get('/login', 'LoginController@index');

// Route for Login user
Route::post('/login', 'LoginController@login');

// Route for register user
Route::get('/register', 'RegisterController@index');

// Route for register user
Route::post('/register', 'RegisterController@register');

// Route for new password
Route::get('/newpassword/(.*)/(.*)', 'NewPasswordController@index');

// Route for new password
Route::post('/newpassword/(.*)/(.*)', 'NewPasswordController@newPassword');

// Route for reset password
Route::get('/resetpassword', 'RessetPasswordController@index' );

// Route for reset password
Route::post('/resetpassword', 'RessetPasswordController@ressetPassword');

// Route for add car by POST method
Route::post('/addcar', 'CarsController@store');

// Route for add brand by POST method
Route::post('/addbrand', 'BrandsController@store');

// Route for add car body by POST method
Route::post('/addcarbody', 'CarBodiesController@store');

// Route for add class of car by POST method
Route::post('/addclass', 'ClassesController@store');

// Route for add fuel od cars by POST method
Route::post('/addfuel', 'FuelsController@store');

// Route for add user by POST method
Route::post('/adduser', 'UsersController@store');

// Route for update brand by POST method
Route::post('/updatebrand', 'BrandsController@update');

// Route for update fuel by POST method
Route::post('/updatefuel', 'FuelsController@update');

// Route for update car body by POST method
Route::post('/updatecarbody', 'CarBodiesController@update');

// Route for update class of cars by POST method
Route::post('/updateclass', 'ClassesController@update');

// Route for update car by POST method
Route::post('/updatecar', 'CarsController@update' );

// Route for update user by POST method
Route::post('/updateuser', 'UsersController@update' );

// Route for get all cars for panel by POST method in JOSN format
Route::post('/getcars', 'CarsController@index');

// Route for get all fuels for panel by GET method in JSON format
Route::post('/getfuels', 'FuelsController@index');

// Route for get all classes of cars for panel by GET method in JSON format
Route::post('/getclasses', 'ClassesController@index');

// Route for get all brands of cars for portal by GET method in JSON format
Route::post('/getbrands', 'BrandsController@index');

// Route for get all car bodies of cars for portal by GET method in JSON format
Route::post('/getcarbodies', 'CarBodiesController@index');

// Route for get all users for portal by GET method in JSON format
Route::post('/getusers', 'UsersController@index');

// Route for get all roles for users by GET method in JSON format
Route::post('/getroles', 'RolesController@index');

// Route for get non allowed ratings by GET method in JSON format
Route::post('/getaratingsonhold',  'RatingsController@ratingsOnHold');

// Route for get rented cars by GET method in JSON format
Route::post('/getrentedcars', 'RentCarsController@carsAllowed');

// Route for get rented cars on hold by GET method in JSON format
Route::post('/getrentedcarsonhold', 'RentCarsController@carsOnHold');

// Route for get all contact messages by GET method in JSON format
Route::post('/getcontactmessages', 'MessagesController@index');

// Route for update info by POST method
Route::post('/updateinfo', 'InformationsController@update');

// Route for update contact informations by POST method
Route::post('/updateconinfo', 'ContactInfoController@update' );

// Route for delete car by POST method
Route::post('/deletecar', 'CarsController@destroy' );

// Route for delete brand by POST method
Route::post('/deletebrand', 'BrandsController@destroy' );

// Route for delete class of car by POST method
Route::post('/deleteclass', 'ClassesController@destroy');

// Route for delete car body of car by POST method
Route::post('/deletecarbody', 'CarBodiesController@destroy' );

// Route for delete fuel of car by POST method
Route::post('/deletefuel', 'FuelsController@destroy');

// Route for delete user by POST method
Route::post('/deleteuser', 'FuelsController@destroy');

// Route for allow rented car by POST method
Route::post('/allowcar', 'RentCarsController@allow');

// Route for disallow rented car from list of rented cars by POST method
Route::post('/disallowcar', 'RentCarsController@disallow' );

// Route for delete rented cars by POST method
Route::post('/deleterentedcar', 'RentCarsController@destroy');

// Route for update rented cars by POST method
Route::post('/updaterentedcar', 'RentCarsController@update');

// Route for reply message by administrator or moderator by POST method
Route::post('/replymessage', 'MessagesController@reply');

// Route for get available cars by GET method
Route::post('/getavailablecars', 'CarsController@availableCars');

// Route for delete message by administrator or moderator by POST method
Route::post('/deletemessage', 'MessagesController@destroy' );

// Route for allow rating by administrator or moderator by POST method
Route::post('/allowrating', 'RatingsController@allowRating');

// Route for delete rating by administrator or moderator by POST method
Route::post('/deleterating', 'RatingsController@destroy');

// Route for get details of your profile
Route::post('/getyourprofile', 'UsersController@show');

// Route for get details of your profile
Route::post('/getyourprofile1', 'UsersController@showAdmin');

// Route for update your profile
Route::post('/updateyourprofile', 'UsersController@update' );

// Route for get activity of site
Route::post('/getactivity', 'ActivityController@index');

// Route for get reviews of pages
Route::post('/getreviewspages', 'ReviewsController@reviews');

// Route for get reviews of cars
Route::post('/getreviewscars', 'ReviewsController@reviewsCars' );

// Route for get all logs of of activity users
Route::post('/getlastlogs', 'LogsController@index');

// Route for get history of rented cars
Route::post('/gethistoryrentedcars', 'RentCarsController@carsDeleted');

// Route for get history of rented cars
Route::get('/logout', function (){
    Helpers::directLogout();
});

// Route for get comments for one car
Route::post('/getcommentscar', 'CommentController@allowedCommentsForOneCar');

// Route for add comment of car
Route::post('/addcomment', 'CommentController@store');

// Route for get comments for one car
Route::post('/allowcomment', 'CommentController@allow');

// Route for add comment of car
Route::post('/disallowcomment', 'CommentController@disallow');

// Route for delete comment of car
Route::post('/deletecomment', 'CommentController@destroy');

// Route for get allowed comments
Route::post('/getallowedcomments', 'CommentController@allowedComments');

// Route for get comments on hold
Route::post('/getcommentsonhold', 'CommentController@nonAllowedComments');

// Route for get rented cars of user
Route::post('/getrentedcarsofuser', 'RentCarsController@userCars');

// Route for get rented cars of user
Route::post('/getpositionofcar', 'RentCarsController@getPositionsofCar');


/*
 * END ROUTES OF PANEL
 */

