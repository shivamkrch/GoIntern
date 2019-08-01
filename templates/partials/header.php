<!DOCTYPE html>
<html lang="en">
  <head>
    <title>GoIntern | Internships that matter</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />

    <!-- Bootstrap CSS -->
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.1.1/css/all.css"
      integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ"
      crossorigin="anonymous"
    />
    <!-- Include styles.css file -->
    <link rel="stylesheet" href="/templates/styles/styles.css" />
  </head>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/config/auth.php'; ?>
  <body>
    <nav
      class="navbar navbar-expand-md navbar-dark bg-primary sticky-top shadow"
    >
      <a class="navbar-brand ml-5" href="/">GoIntern</a>
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto mr-5">
          <?php if(!isAuthenticated()){ ?>
          <!-- Show when user is not logged in -->
          <li class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              id="navbarDropdown"
              role="button"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              Register
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="/student/register">Student</a>
              <a class="dropdown-item" href="/employer/register"
                >Employer</a
              >
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/login">Login</a>
          </li>
          <?php } if(isAuthenticated()){ 
            ?>
            <!-- Show when user is logged in -->
          <li class="nav-item dropdown px-2 ml-2 mt-2 mt-md-0" id="userDropDown">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              id="navbarDropdown"
              role="button"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
            <?php if($_COOKIE['user-type']=='std'){ ?>
              <!-- Set icon if user is student -->
              <i class="far fa-user mr-1"></i> 
            <?php }else{ ?>
              <!-- Set icon if user is an employer -->
              <i class="fas fa-hotel mr-1" style="font-size: 1rem;"></i> 
            <?php } ?>
            <!-- Show the name of the user -->
              <?=$res['name'] ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <?php if($_COOKIE['user-type']=='emp') { ?>
                <!-- Show menu option only when the logged in user is an employer -->
                <a class="dropdown-item" href="/employer/internships">My Posts</a>
              <?php }else { ?>
                <a class="dropdown-item" href="/student/applications">My Applications</a>
              <?php } ?>
              <a class="dropdown-item" href="/logout">Logout</a>
            </div>
          </li>
          <?php } ?>
        </ul>
      </div>
    </nav>
  </body>
</html>
