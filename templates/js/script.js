window.onload = function() {
  // Show and Hide Password in password fields
  $("#showPwdBtn").click(function(e) {
    e.preventDefault();
    if ($("#showPwdIcon").hasClass("fa-eye")) {
      $("#pwdField")
        .attr("type", "text")
        .focus();
      $("#showPwdBtn").attr("title", "Hide Password");
      $("#showPwdIcon")
        .removeClass("fa-eye")
        .addClass("fa-eye-slash");
    } else {
      $("#pwdField")
        .attr("type", "password")
        .focus();
      $("#showPwdBtn").attr("title", "Show Password");
      $("#showPwdIcon")
        .removeClass("fa-eye-slash")
        .addClass("fa-eye");
    }
  });

  // Get form data as JSON
  function getFormDataAsJSON(form) {
    let formData = form.serializeArray();
    let data = {};
    formData.forEach(inp => {
      data[inp.name] = inp.value;
    });
    return data;
  }

  // Student Registration
  $("#stdRegForm").submit(function(e) {
    e.preventDefault();
    let stdData = getFormDataAsJSON($(this));
    $.post(
      "/api/student/register",
      stdData,
      function(res) {
        if (res.email) {
          $("#regError")
            .text(res.email)
            .fadeIn("fast");
          return;
        } else if (!res) {
          $("#regError")
            .text("Unable to register. please try again.")
            .fadeIn("fast");
          return;
        }
        if (res) {
          window.location = "/login";
        }
      },
      "json"
    );
  });

  // Employer Registration
  $("#empRegForm").submit(function(e) {
    e.preventDefault();
    let empData = getFormDataAsJSON($(this));
    $.post(
      "/api/employer/register",
      empData,
      function(res) {
        if (res.email) {
          $("#regError")
            .text(res.email)
            .fadeIn("fast");
          return;
        } else if (!res) {
          $("#regError")
            .text("Unable to register. please try again.")
            .fadeIn("fast");
          return;
        }
        if (res) {
          window.location = "/login";
        }
      },
      "json"
    );
  });

  // Login
  $("#loginForm").submit(function(e) {
    e.preventDefault();
    let loginData = getFormDataAsJSON($(this));
    $.post(
      "/api/login",
      loginData,
      function(res) {
        if (res == true) {
          window.location = "/";
        } else {
          $("#regError").fadeIn("fast");
        }
      },
      "json"
    );
  });

  function appendLeadingZeroes(n) {
    if (n <= 9) {
      return "0" + n;
    }
    return n;
  }

  // Set employer id to the hidden field in the add-internship modal
  $("#addInternshipModal").on("show.bs.modal", function(event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var empId = button.data("empid");
    $("input#empId").val(empId);
    let today = new Date();
    let lastDate = `${today.getFullYear()}-${appendLeadingZeroes(
      today.getMonth() + 1
    )}-${appendLeadingZeroes(today.getDate() + 3)}`;
    today.setDate(today.getDate() + 5);
    let startDate = `${today.getFullYear()}-${appendLeadingZeroes(
      today.getMonth() + 1
    )}-${appendLeadingZeroes(today.getDate())}`;
    $("#intLastDate").attr("min", lastDate);
    $("#intStartDate").attr("min", startDate);
  });

  // Add Internship
  $("#addInternshipForm").submit(function(e) {
    e.preventDefault();
    let internshipData = getFormDataAsJSON($(this));
    $.post(
      "/api/employer/internship",
      internshipData,
      function(res) {
        if (res) {
          window.location.reload();
        } else {
          $("#addIntError").fadeIn("fast");
        }
      },
      "json"
    );
  });

  // Apply for internship
  $("#applyBtn").click(function(e) {
    e.preventDefault();
    let stdId = $(this).attr("data-stdid");
    let intId = $(this).attr("data-intid");
    $.post(
      `/api/apply/${intId}`,
      function(res) {
        if (res == true) {
          window.location = "/student/applications";
        } else {
          window.location = "/login";
        }
      },
      "json"
    );
  });

  // Update application selected status
  $(".selBtn").click(function(e) {
    e.preventDefault();
    let appId = $(this).attr("app-id");
    const data = { selected: $(this).text() == "Hire" ? 1 : 0 };
    $.post(
      `/api/application/${appId}`,
      data,
      function(res) {
        if (res == true) {
          window.location.reload();
        } else {
          window.location = "/login";
        }
      },
      "json"
    );
  });

  $(function() {
    $('[data-toggle="tooltip"]').tooltip();
  });
};
