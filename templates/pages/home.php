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
$db->query("SELECT * FROM internship_posts WHERE last_date>now() ORDER BY date DESC");
$internships = $db->resultset();
?>
<div class="container p-3 my-4">
    <h3>Internships Available</h3>
    <div class="container-fluid px-0">
        <?php 
        if(count($internships) > 0){
            foreach ($internships as $internship) { 
            $db->query("SELECT name from employers WHERE emp_id={$internship['emp_id']}");
            $empName = $db->single()['name'];
            ?>
                <div class="card m-3 shadow-sm col-12 bg-light">
                    <div class="card-body pb-1 px-1">
                        <h5 class="card-title">
                            <a href="/internship/<?=$internship['int_id'] ?>">
                                <?=$internship['title'] ?>
                            </a>
                        </h5>
                        <h6 class="card-subtitle mb-2 text-secondary"><?=$empName ?></h6>
                        <div class="row text-left ml-0">
                            <div class="col-md-6 px-0">
                                <p class="mb-1">
                                    <strong class="mr-2">Location:</strong>
                                    <?=ucwords($internship['location']) ?>
                                </p>
                                <p class="mb-1">
                                    <strong class="mr-2">Posted On:</strong>
                                    <?=date("j M, Y", strtotime($internship['date'])) ?>
                                </p>
                                <p class="mb-1">
                                    <strong class="mr-2">Apply By:</strong>
                                    <?=date("j M, Y", strtotime($internship['last_date'])) ?>
                                </p>
                            </div>
                            <div class="col-md-6 px-0">
                                <p class="mb-1">
                                    <strong class="mr-2">Start Date:</strong>
                                    <?=date("j M, Y", strtotime($internship['start_date'])) ?>
                                </p>
                                <p class="mb-1">
                                    <strong class="mr-2">Duration:</strong>
                                    <?=$internship['duration'] ?>
                                </p>
                                <p class="mb-1">
                                    <strong class="mr-2">Stipend:</strong>
                                    <?=(explode(" ", $internship['stipend'])[0] == 0
                                    ? "Unpaid" : "₹ ".$internship['stipend']) ?>
                                </p>
                            </div>
                        </div>
                        <div class="container text-right mt-1">
                            <a href="/internship/<?=$internship['int_id'] ?>" 
                            class="btn btn-outline-info m-2 card-link">View Details</a>
                        </div>
                    </div>
                </div>
        <?php }
        }else{ ?>
            <h5 class="text-danger text-center mt-5">No internships.</h5>
        <?php } ?>
    </div>
</div>
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/partials/footer.html'; ?>
