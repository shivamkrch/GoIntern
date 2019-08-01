<?php
// Check if user is logged in
function isAuthenticated(){
    if(isset($_COOKIE['user-email']) && isset($_COOKIE['user-type'])){
        return TRUE;
    }else{
        return FALSE;
    }
}