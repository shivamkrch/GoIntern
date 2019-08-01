<?php 
// If user is logged in, get his details
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

include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/partials/header.php'; 

$db->query("SELECT * FROM internship_posts WHERE emp_id=:emp_id ORDER BY date DESC");
$db->bind(":emp_id", $emp_id);
$internships = $db->resultset();

?>
<div class="container p-3 my-4">
    <h3>Internships Posted
        <button type="button" class="btn btn-success float-right m-2" data-toggle="modal"
         data-target="#addInternshipModal" data-empid="<?=$emp_id ?>">
          <i class="fa fa-plus mr-1"></i> Add Internship
        </button>
    </h3>
    <div class="container-fluid px-0">
        <?php 
        include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/partials/add-internship-modal.html'; 
        if(count($internships) > 0){
            foreach ($internships as $internship) { 
            $db->query("SELECT COUNT(app_id) as num_app from applications WHERE 
            int_id={$internship['int_id']}");
            $numApp = $db->single()['num_app'];
            ?>
                <div class="card m-3 shadow-sm col-12 bg-light">
                    <div class="card-body pb-1 px-1">
                        <h5 class="card-title">
                            <a href="/internship/<?=$internship['int_id'] ?>">
                                <?=$internship['title'] ?>
                            </a>
                        </h5>
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
                        <div class="container text-center mt-1">
                            <p class="mb-1"><strong>No. of Applicants: </strong> <?=$numApp ?></p>
                            <a href="/internship/<?=$internship['int_id'] ?>" 
                            class="btn btn-outline-info m-2 card-link">View Details</a>
                            <?php if($numApp > 0){ ?>
                                <a href="/employer/applications/<?=$internship['int_id'] ?>" 
                                class="btn btn-primary m-2 card-link">View Applications</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
        <?php }
        }else{ ?>
            <h5 class="text-danger text-center mt-5">No internships.</h5>
        <?php } ?>
    </div>
</div>
<script>
    document.title = "<?=$res['name'] ?> | Internships | GoIntern";
</script>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/partials/footer.html'; ?>
