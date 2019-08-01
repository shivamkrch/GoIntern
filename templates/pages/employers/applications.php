<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/config/db.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/config/auth.php';

if(isAuthenticated()){
    // If user is logged in, get his details
    $db = new DB();
    $db->query("SELECT * FROM ".($_COOKIE['user-type']=='emp'?'employers':'students').
    " WHERE email=:email");
    $db->bind(':email', $_COOKIE['user-email']);
    $res = $db->single();
    // If no user found go to login page by logging out the user and clearing cookies
    if($res == ''){
        header('location: /logout');
    }
    if($_COOKIE['user-type']=='emp'){
        $emp_id = $res['emp_id'];
    }
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/partials/header.php';

$intId = constant("INT_ID");
$db = new DB();
$db->query("SELECT * FROM internship_posts INNER JOIN employers WHERE int_id={$intId}");
$internship = $db->single();
$db->query("SELECT * FROM applications WHERE int_id={$intId} ORDER BY date DESC");
$applications = $db->resultset();
?>
<div class="container p-3 my-4">
    <h4><?=(count($applications) == 0 ? "" : count($applications)) ?> 
    Application<?=(count($applications) > 1 ? "s" : "") ?> for 
        <a href="/internship/<?=$intId ?>" target="_blank" rel="noopener noreferrer">
            <?=$internship['title']." at ".$internship['name'] ?>
        </a>
    </h4>
    <div class="container-fluid px-0">
        <?php 
        if(count($applications) > 0){
            foreach ($applications as $application) { 
                $db->query("SELECT name, skills from students WHERE std_id={$application['std_id']}");
                $student = $db->single();
            ?>
                <div class="card m-3 shadow-sm col-12 bg-light">
                    <div class="card-body px-1">
                        <div class="row text-left ml-0">
                            <div class="col-md-6 px-0">
                                <h4 class="card-title text-primary mb-3">
                                    <?=$student['name'] ?>
                                </h4>
                                <h6 class="card-subtitle mb-2 text-dark">
                                    <strong class="mr-2">Skills:</strong>
                                            <?=$student['skills'] ?>
                                </h6>
                            </div>
                            <div class="col-md-6 px-0">
                                <p class="mb-1">
                                    <strong class="mr-2">Applied On:</strong>
                                    <?=date("j M, Y", strtotime($application['date'])) ?>
                                </p>
                                <p class="mb-1">
                                    <strong class="mr-2">Application Status:</strong>
                                    <?php
                                    if($application['selected'] == NULL){ ?>
                                        <span class="badge badge-pill badge-primary p-2">Applied</span>
                                    <?php }elseif($application['selected'] == 1){ ?>
                                        <span class="badge badge-pill badge-success p-2">Hired</span>
                                    <?php }else{ ?>
                                        <span class="badge badge-pill badge-danger p-2">Not Selected</span>
                                    <?php } ?>
                                </p>
                            </div>
                        </div>
                        <div class="container text-center mt-3 px-0">
                            <?php
                            if($application['selected'] == NULL){ ?>
                                <button class="btn btn-success mx-2 px-4 selBtn"
                                 app-id="<?=$application['app_id'] ?>">Hire</button>
                                <button class="btn btn-danger mx-2 selBtn"
                                    app-id="<?=$application['app_id'] ?>">Reject</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
        <?php }
        }else{ ?>
            <h5 class="text-danger text-center mt-5">No applications.</h5>
        <?php } ?>
    </div>
</div>
<script>
    document.title = "My Applications | <?=$res['name'] ?> | GoIntern";
</script>
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/partials/footer.html'; ?>
