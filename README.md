examination-system
==================

This is an Online Examination System based on PHP, jQuery, and can be used for basic quiz purposes, and to host online examination in colleges.

It features the Registration functionality with the verification using an EMAIL Account. And allows only one login for an account in anywhere. Multiple Logins cant be initialised.

The Questionnaire page made with jQuery consist of the questions along with NEXT and PREVIOUS button, and a COUNTDOWN TIMER that can be altered according to the need. 

		     startDate : "2014/11/13 06:00:00"   //the start time of the exam.
        dateAndTime : "2014/11/13 09:00:00"  // the end time of the exam.

This page calls an event on a key press that logs the user out terminating the examination.

The db/examination.sql file contains the database that must be imported on phpmyadmin(mySQL) implemented using PHP Data Objects (PDO). 
