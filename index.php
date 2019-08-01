<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/config/db.php';
require_once __DIR__.'/config/auth.php';

// Initializing router
$router = new Klein\Klein();

// Home page route
$router->respond('GET', '/', function($req, $res, $service){
    if(isAuthenticated() && $_COOKIE['user-type'] == 'emp'){
        header('location: /employer/internships');
    }
    $service->render(__DIR__.'/templates/pages/home.php');
});

// Login page route
$router->respond('GET', '/login', function($req, $res, $service){
    if(isAuthenticated()){
        header('location: /');
    }
    $service->render(__DIR__.'/templates/pages/login.php');
});

// Logout page route
$router->respond('GET', '/logout', function($req, $res, $service){
    // Remove both saved cookies
    setcookie('user-email', null, time()-1, '/');
    setcookie('user-type', null, time()-1, '/');
    $res->redirect('/login');
});

// Internship Details page route
$router->respond('GET', '/internship/[i:intId]', function($req, $res, $service){
    define("INT_ID", $req->intId);
    $service->render(__DIR__.'/templates/pages/internship-detail.php');
});


// Routes for employers
// Routes starting with /employer
$router->with('/employer', function() use ($router){

    // Employer registration page route
    $router->respond('/register', function($req, $res, $service){
        if(isAuthenticated()){
            header('location: /orders');
        }
        $service->render(__DIR__.'/templates/pages/employers/register.php');
    });

    // Employer internships page route
    $router->respond('/internships', function($req, $res, $service){
        if(!(isAuthenticated() && $_COOKIE['user-type'] == 'emp')){
            header('location: /login');
        }
        $service->render(__DIR__.'/templates/pages/employers/internships.php');
    });

    // Employer internships page route
    $router->respond('/applications/[i:intId]', function($req, $res, $service){
        if(!(isAuthenticated() && $_COOKIE['user-type'] == 'emp')){
            header('location: /login');
        }
        define('INT_ID', $req->intId);
        $service->render(__DIR__.'/templates/pages/employers/applications.php');
    });

});

// Routes starting with /student
$router->with('/student', function() use ($router){

    // Student registration route
    $router->respond('/register', function($req, $res, $service){
        if(isAuthenticated()){
            header('location: /');
        }
        $service->render(__DIR__.'/templates/pages/students/register.php');
    });

    // Student applications route
    $router->respond('/applications', function($req, $res, $service){
        if(!(isAuthenticated() && $_COOKIE['user-type'] == 'std')){
            header('location: /');
        }
        $service->render(__DIR__.'/templates/pages/students/applications.php');
    });
});

// API routes, starting with /api
$router->with('/api', function() use ($router){

    // Routes starting with /api/student
    $router->with('/student', function() use ($router){

        // Route /api/student/register
        // Register new student
        $router->respond('POST','/register', function($req, $res, $service){
            if(isset($req->name) && isset($req->email) && isset($req->password) && isset($req->skills)){
                $db = new DB();
                $db->query("SELECT email FROM `students` WHERE email=:email");
                $db->bind(':email', $req->email);
                $resStd = $db->resultset();
                $db->query("SELECT email FROM `employers` WHERE email=:email");
                $db->bind(':email', $req->email);
                $resEmp = $db->resultset();
                if (count($resStd) != 0 || count($resEmp) != 0) {
                    $result['email'] = "Email already exists. Please try another.";
                    return json_encode($result);
                }else{
                    $db->bind(':name', $req->name);
                    $pwd = md5($req->password);
                    $db->query("INSERT INTO `students` (name, email, pwd, skills) 
                    VALUES(:name, :email, :pwd, :skills)");
                    $db->bind(':name', $req->name);
                    $db->bind(':email', $req->email);
                    $db->bind(':pwd', $pwd);
                    $db->bind(':skills', $req->skills);
                    $result = $db->execute();
                    if (!is_null($db->queryError())) {
                        return json_encode('false');
                    }
                    return json_encode($result);
                }
                $db->terminate();
            }else{
                return "Invalid request";
            }
        });

        
    });

    // Routes starting with /api/employer
    $router->with('/employer', function() use ($router){

        // Route /api/employer/register
        // Register new employer
        $router->respond('POST','/register', function($req, $res, $service){
            if(isset($req->name) && isset($req->email) && isset($req->password) && isset($req->location)){
                $db = new DB();
                $db->query("SELECT email FROM `students` WHERE email=:email");
                $db->bind(':email', $req->email);
                $resStd = $db->resultset();
                $db->query("SELECT email FROM `employers` WHERE email=:email");
                $db->bind(':email', $req->email);
                $resEmp = $db->resultset();
                if (count($resStd) != 0 || count($resEmp) != 0) {
                    $result['email'] = "Email already exists. Please try another.";
                    return json_encode($result);
                }else{
                    $db->bind(':name', $req->name);
                    $pwd = md5($req->password);
                    $db->query("INSERT INTO `employers` (name, email, pwd, location) 
                    VALUES(:name, :email, :pwd, :loc)");
                    $db->bind(':name', $req->name);
                    $db->bind(':email', $req->email);
                    $db->bind(':pwd', $pwd);
                    $db->bind(':loc', $req->location);
                    $result = $db->execute();
                    if ($db->queryError()) {
                        return json_encode('false');
                    }
                    return json_encode($result);
                }
                $db->terminate();
            }else{
                return "Invalid request";
            }
        });

        // Route /api/employer/internship
        // Add new internship
        $router->respond('POST','/internship', function($req, $res, $service){
            if(isset($req->intTitle) && isset($req->intLoc) && isset($req->intResp) && 
                isset($req->intSkills) && isset($req->intLastDate) && isset($req->intStartDate)
                && isset($req->intStipendNum) && isset($req->intStipendUnit) && 
                isset($req->intDurNum) && isset($req->intDurUnit) &&isset($req->empId)){
                $db = new DB();
                $db->query("INSERT INTO internship_posts (title, location, start_date, last_date,
                skills_req, responsibilities, stipend, duration, emp_id) VALUES(:title, :location, 
                    :start_date, :last_date, :skills, :resp, :stipend, :duration, :emp_id)");
                    $db->bind(":title", $req->intTitle);
                    $db->bind(":location", $req->intLoc);
                    $db->bind(":start_date", $req->intStartDate);
                    $db->bind(":last_date", $req->intLastDate);
                    $db->bind(":skills", $req->intSkills);
                    $db->bind(":resp", $req->intResp);
                    $db->bind(":stipend", $req->intStipendNum." /".$req->intStipendUnit);
                    $db->bind(":duration", $req->intDurNum." ".$req->intDurUnit);
                    $db->bind(":emp_id", $req->empId);
                    $db->execute();
                    if(!$db->queryError()){
                        return json_encode(TRUE);
                    }else {
                        return json_encode(FALSE);
                    }
                $db->terminate();
            }else{
                return json_encode(FALSE);
            }
        });

    });

    // Route /api/login
    // User login route
    $router->respond('POST', '/login', function($req, $res, $service) use($router){
        if(isset($req->email) && isset($req->password)){
            $db = new DB();
            // Select from both employers and students tables
            $db->query("SELECT pwd FROM `students` WHERE email=:email");
            $db->bind(':email', $req->email);
            $resStd = $db->single();
            $db->query("SELECT pwd FROM `employers` WHERE email=:email");
            $db->bind(':email', $req->email);
            $resEmp = $db->single();
            $pwd = md5($req->password);
            // Check for same password for student if exists
            if($pwd == $resStd['pwd']){
                // Set cookie with key 'user-email' to matched student's email
                setcookie('user-email', $req->email, time()+3600*24*20, '/');
                // Set cookie with key 'user-type' to 'std' representing students
                setcookie('user-type', 'std', time()+3600*24*20, '/');
                return json_encode(TRUE);
            // Check for same password for employer if exists
            }elseif($pwd == $resEmp['pwd']){
                // Set cookie with key 'user-email' to matched employer's email
                setcookie('user-email', $req->email, time()+3600*24*20, '/');
                // Set cookie with key 'user-type' to 'emp' representing employer
                setcookie('user-type', 'emp', time()+3600*24*20, '/');
                return json_encode(TRUE);
            }else{
                return json_encode(FALSE);
            }
            $db->terminate();
        }else{
            return "Invalid request";
        }
    });

    // Route /api/apply/:intId
    // Apply for internship
    $router->respond('POST', '/apply/[i:intId]', function($req, $res, $service) use($router){
        if(isAuthenticated() && $_COOKIE['user-type'] == 'std'){
            $db = new DB();
            $db->query("SELECT emp_id FROM internship_posts WHERE int_id=:intId");
            $db->bind(":intId", $req->intId);
            $empId = $db->single()['emp_id'];
            $db->query("SELECT std_id FROM students WHERE email=:email");
            $db->bind(":email", $_COOKIE['user-email']);
            $stdId = $db->single()['std_id'];
            $db->query("INSERT INTO applications (std_id, emp_id, int_id) VALUES(:std, :emp, :int)");
            $db->bind(":std", $stdId);
            $db->bind(":emp", $empId);
            $db->bind(":int", $req->intId);
            $db->execute();
            if(!$db->queryError()){
                return json_encode(TRUE);
            }else{
                return json_encode(FALSE);
            }
            $db->terminate();
        }else{
            return json_encode(FALSE);
        }
    });

    // Route /api/application/:appId
    // Apply for internship
    $router->respond('POST', '/application/[i:appId]', function($req, $res, $service) use($router){
        if(isAuthenticated() && $_COOKIE['user-type'] == 'emp'){
            $db = new DB();
            $db->query("UPDATE applications SET selected=:sel WHERE app_id=:appId");
            $db->bind(":sel", $req->selected);
            $db->bind(":appId", $req->appId);
            $db->execute();
            if(!$db->queryError()){
                return json_encode(TRUE);
            }else{
                return json_encode(FALSE);
            }
            $db->terminate();
        }else{
            return json_encode(FALSE);
        }
    });
    
});

$router->dispatch();