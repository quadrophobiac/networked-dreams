/* 
a functions file for validating the user input of join.php
 */


function validateName(field) {
    if (field == "") {
        return "No Forename was entered.\n";
    }
    return "";
} // end validateName

function validateUALIAS(field) {
    if (field == "") {
        return "No Username was entered.\n";
    } else if (field.length < 5) {
        return "Usernames must be at least 5 characters.\n";
    } else if (/[^a-zA-Z0-9_-]/.test(field)) {
        // alt regex - /^[A-Za-z0-9_]{1,20}$/
        return "Only a-z, A-Z, 0-9, - and _ allowed in Usernames.\n";
    }
    return "";
} // end validateUALIAS

function validatePassword(field) {
    if (field == "") {
        return "No Password was entered.\n";
    } else if (field.length < 6) {
        return "Passwords must be at least 6 characters.\n";              
    } else if (! /[A-Z]/.test(field) || !/[0-9]/.test(field)) {
        return "For security please include caps and numbers!\n";
        // alt regex /^[A-Za-z0-9!@#$%^&*()_]{6,20}$/
    }
    return "";
} // end validate PWD

function validateEmail(field) {
    if (field == "") {
        return "No Email was entered.\n";
    } else if (!((field.indexOf(".") > 0) && (field.indexOf("@") > 0)) || /[^a-zA-Z0-9.@_-]/.test(field)) {
        return "The Email address is invalid.\n";
    }
    return "";
}