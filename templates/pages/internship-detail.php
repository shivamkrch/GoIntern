<?php 
// If user is logged in, get his details
if(isAuthenticated()){
    $db = new DB();
    $db->query("SELECT * FROM ".($_COOKIE['user-type']=='emp'?'employers':'students')." WHERE email=:email");
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

include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/partials/header.php'; 

$intId = constant("INT_ID");
$db = new DB();
$db->query("SELECT * FROM internship_posts WHERE int_id=:int_id");
$db->bind(":int_id", $intId);
$internship = $db->single();

?>
<div class="container p-3 my-4">
        <?php 
        if($internship != ''){
            $db->query("SELECT name from employers WHERE emp_id={$internship['emp_id']}");
            $empName = $db->single()['name'];
            ?>
                <div class="card m-3 shadow-sm col-12 bg-light">
                    <div class="card-body px-1">
                        <h4 class="card-title"><?=$internship['title'] ?></h4>
                        <h6 class="card-subtitle mb-2 text-secondary">
                            <strong><?=$empName ?></strong></h6>
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
                        
                    </div>
                </div>
                <div class="container text-center mt-4 p-x-5">
                    <p class="mb-1 text-left">
                        <strong class="mr-2">Responsibilities:</strong><br>
                        <?=$internship['responsibilities']?>
                    </p>
                    <p class="mb-1 text-left">
                        <strong class="mr-2">Skills Required:</strong>
                        <?=$internship['skills_req']?>
                    </p>
                    <?php if(!isAuthenticated() || $_COOKIE['user-type'] == 'std'){
                        $appId = '';
                        if(isAuthenticated()){
                            $db->query("SELECT app_id FROM applications WHERE std_id=:stdId AND int_id=:intId");
                            $db->bind(":stdId", $res['std_id']);
                            $db->bind(":intId", $internship['int_id']);
                            $appId = $db->single(); 
                        } ?>
                            <button class="btn btn-primary px-5 mt-4" id="applyBtn" 
                            data-intid="<?=$intId ?>" <?=($appId==''? "" : "disabled") ?>>
                                <?=($appId==''? "APPLY" : "APPLIED") ?>
                            </button>
                        <?php
                    }
                    ?>
                </div>
        <?php } else { ?>
            <h5 class="text-danger text-center mt-5">No internship found.</h5>
        <?php } ?>
</div>
<script>
    document.title = "<?=$internship['title'] ?> at <?=$empName ?> | GoIntern";
</script>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/partials/footer.html'; ?>
