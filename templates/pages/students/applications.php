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

$db = new DB();
$db->query("SELECT * FROM applications WHERE std_id={$res['std_id']}
 ORDER BY date DESC");
$applications = $db->resultset();
?>
<div class="container p-3 my-4">
    <h3>My Applications</h3>
    <div class="container-fluid px-0">
        <?php 
        if(count($applications) > 0){
            foreach ($applications as $application) { 
                $db->query("SELECT name from employers WHERE emp_id={$application['emp_id']}");
                $empName = $db->single()['name'];
                $db->query("SELECT title FROM internship_posts WHERE int_id={$application['int_id']}");
                $intTitle = $db->single()['title'];
                $db->query("SELECT COUNT(app_id) AS num_app FROM applications WHERE 
                    int_id={$application['int_id']}");
                $numApp = $db->single()['num_app'];
            ?>
                <div class="card m-3 shadow-sm col-12 bg-light">
                    <div class="card-body pb-1 px-1">
                        <h5 class="card-title">
                            <?=$intTitle ?>
                        </h5>
                        <h6 class="card-subtitle mb-2 text-secondary"><?=$empName ?></h6>
                        <div class="row text-left ml-0">
                            <div class="col-md-6 px-0">
                                <p class="mb-1">
                                    <strong class="mr-2">Applied On:</strong>
                                    <?=date("j M, Y", strtotime($application['date'])) ?>
                                </p>
                                <p class="mb-1">
                                    <strong class="mr-2">No. of Applicants:</strong>
                                    <?=$numApp ?>
                                </p>
                            </div>
                            <div class="col-md-6 px-0">
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
                        <div class="container text-right mt-1">
                            <a href="/internship/<?=$application['int_id'] ?>" 
                            class="btn btn-outline-info m-2 card-link">View Details</a>
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
