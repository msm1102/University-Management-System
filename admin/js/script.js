console.log("JS Connected Successfully");

// ==========================
// DASHBOARD COUNTS
// ==========================

function updateDashboardCounts() {
  let students = JSON.parse(localStorage.getItem("students")) || [];

  let faculties = JSON.parse(localStorage.getItem("faculties")) || [];

  let courses = JSON.parse(localStorage.getItem("courses")) || [];

  let fees = JSON.parse(localStorage.getItem("fees")) || [];

  let pendingFees = fees.filter((fee) => fee.status === "Pending");

  let studentCard = document.getElementById("totalStudents");

  let facultyCard = document.getElementById("totalFaculty");

  let courseCard = document.getElementById("totalCourses");

  let feeCard = document.getElementById("pendingFees");

  if (studentCard) studentCard.innerHTML = students.length;

  if (facultyCard) facultyCard.innerHTML = faculties.length;

  if (courseCard) courseCard.innerHTML = courses.length;

  if (feeCard) feeCard.innerHTML = pendingFees.length;
}

// ==========================
// LIVE CLOCK
// ==========================

function updateClock() {
  let clock = document.getElementById("clock");

  if (!clock) return;

  let now = new Date();

  clock.innerHTML = now.toLocaleTimeString();
}

setInterval(updateClock, 1000);

// ==========================
// STUDENT CRUD
// ==========================

function addStudent() {
  let id = document.getElementById("studentId").value;

  let name = document.getElementById("studentName").value;

  let email = document.getElementById("studentEmail").value;

  let phone = document.getElementById("studentPhone").value;

  let department = document.getElementById("studentDepartment").value;

  let semester = document.getElementById("studentSemester").value;

  if (id === "" || name === "" || email === "") {
    alert("Please fill all fields");
    return;
  }

  let students = JSON.parse(localStorage.getItem("students")) || [];

  let editIndex = localStorage.getItem("studentEditIndex");

  let student = {
    id,
    name,
    email,
    phone,
    department,
    semester,
  };

  if (editIndex !== null) {
    students[editIndex] = student;

    localStorage.removeItem("studentEditIndex");

    alert("Student Updated");
  } else {
    students.push(student);

    alert("Student Added");
  }

  localStorage.setItem("students", JSON.stringify(students));

  displayStudents();

  updateDashboardCounts();

  document.getElementById("studentForm").reset();
}

function displayStudents() {
  let table = document.getElementById("studentTableBody");

  if (!table) return;

  let students = JSON.parse(localStorage.getItem("students")) || [];

  table.innerHTML = "";

  students.forEach(function (student, index) {
    table.innerHTML += `

        <tr>

            <td>${student.id}</td>
            <td>${student.name}</td>
            <td>${student.department}</td>
            <td>${student.semester}</td>
            <td>${student.email}</td>

            <td>

                <button
                class="btn btn-warning btn-sm"
                onclick="editStudent(${index})">

                Edit

                </button>

                <button
                class="btn btn-danger btn-sm"
                onclick="deleteStudent(${index})">

                Delete

                </button>

            </td>

        </tr>

        `;
  });
}

function editStudent(index) {
  let students = JSON.parse(localStorage.getItem("students")) || [];

  let student = students[index];

  document.getElementById("studentId").value = student.id;

  document.getElementById("studentName").value = student.name;

  document.getElementById("studentEmail").value = student.email;

  document.getElementById("studentPhone").value = student.phone;

  document.getElementById("studentDepartment").value = student.department;

  document.getElementById("studentSemester").value = student.semester;

  localStorage.setItem("studentEditIndex", index);
}

function deleteStudent(index) {
  if (confirm("Delete Student?")) {
    let students = JSON.parse(localStorage.getItem("students")) || [];

    students.splice(index, 1);

    localStorage.setItem("students", JSON.stringify(students));

    displayStudents();

    updateDashboardCounts();
  }
}

// ==========================
// FACULTY CRUD
// ==========================

function addFaculty() {
  let faculty_id = document.getElementById("facultyId").value;
  let name = document.getElementById("facultyName").value;
  let email = document.getElementById("facultyEmail").value;
  let phone = document.getElementById("facultyPhone").value;
  let department = document.getElementById("facultyDepartment").value;
  let designation = document.getElementById("facultyDesignation").value;

  if (faculty_id == "" || name == "" || email == "") {
    alert("Please fill all fields");
    return;
  }

  fetch("../save_faculty.php", {
    method: "POST",

    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },

    body:
      "faculty_id=" +
      encodeURIComponent(faculty_id) +
      "&name=" +
      encodeURIComponent(name) +
      "&email=" +
      encodeURIComponent(email) +
      "&phone=" +
      encodeURIComponent(phone) +
      "&department=" +
      encodeURIComponent(department) +
      "&designation=" +
      encodeURIComponent(designation),
  })
    .then((res) => res.text())
    .then((data) => {
      alert(data);

      document.getElementById("facultyForm").reset();
      displayFaculties();
    })
    .catch((err) => console.log(err));
}
function displayFaculties() {
  let table = document.getElementById("facultyTableBody");
  if (!table) return;

  let url = window.location.pathname.includes("/pages/") ? "../get_faculties.php" : "get_faculties.php";

  fetch(url)
    .then((response) => response.json())
    .then((faculties) => {
      table.innerHTML = "";
      faculties.forEach(function (faculty) {
        table.innerHTML += `
                <tr>
                    <td>${faculty.faculty_id}</td>
                    <td>${faculty.name}</td>
                    <td>${faculty.department}</td>
                    <td>${faculty.designation}</td>
                    <td>${faculty.email}</td>

                    <td>
                        <button class="btn btn-warning btn-sm">
                            Edit
                        </button>
                        <button class="btn btn-danger btn-sm">
                            Delete
                        </button>
                    </td>
                </tr>
            `;
      });
    })
    .catch((err) => console.log("Faculty fetch error:", err));
}
function editFaculty(index) {
  let faculties = JSON.parse(localStorage.getItem("faculties")) || [];

  let faculty = faculties[index];

  document.getElementById("facultyId").value = faculty.id;

  document.getElementById("facultyName").value = faculty.name;

  document.getElementById("facultyEmail").value = faculty.email;

  document.getElementById("facultyPhone").value = faculty.phone;

  document.getElementById("facultyDepartment").value = faculty.department;

  document.getElementById("facultyDesignation").value = faculty.designation;

  localStorage.setItem("facultyEditIndex", index);
}

function deleteFaculty(index) {
  if (confirm("Delete Faculty?")) {
    let faculties = JSON.parse(localStorage.getItem("faculties")) || [];

    faculties.splice(index, 1);

    localStorage.setItem("faculties", JSON.stringify(faculties));

    displayFaculties();

    updateDashboardCounts();
  }
}

function getApiUrl(endpoint) {
  let inPages = window.location.pathname.includes("/pages/");
  if (window.location.protocol === "file:") return null;
  return inPages ? "../../api/" + endpoint : "../api/" + endpoint;
}

// ==========================
// STUDENT CRUD (MySQL + API)
// ==========================

function addStudent() {
  let id = document.getElementById("studentId").value.trim();
  let name = document.getElementById("studentName").value.trim();
  let email = document.getElementById("studentEmail").value.trim();
  let phone = document.getElementById("studentPhone").value.trim();
  let department = document.getElementById("studentDepartment").value.trim();
  let semester = document.getElementById("studentSemester").value.trim();

  if (id === "" || name === "" || email === "") {
    alert("Please fill all required student fields");
    return;
  }

  let apiUrl = getApiUrl("students.php");

  if (apiUrl) {
    fetch(apiUrl, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id, name, email, phone, department, semester })
    })
      .then(res => res.json())
      .then(data => {
        alert(data.message || "Student Saved to MySQL Database!");
        displayStudents();
        document.getElementById("studentForm").reset();
      })
      .catch(err => alert("DB Error: " + err));
  } else {
    // LocalStorage fallback for file://
    let students = JSON.parse(localStorage.getItem("students")) || [];
    students.push({ id, name, email, phone, department, semester });
    localStorage.setItem("students", JSON.stringify(students));
    displayStudents();
    document.getElementById("studentForm").reset();
    alert("Student Saved Locally");
  }
}

function displayStudents() {
  let table = document.getElementById("studentTableBody");
  if (!table) return;

  let apiUrl = getApiUrl("students.php");

  if (apiUrl) {
    fetch(apiUrl)
      .then(res => res.json())
      .then(students => {
        renderStudentTable(table, students);
        let el = document.getElementById("totalStudents");
        if (el) el.innerHTML = students.length;
      })
      .catch(() => {
        let students = JSON.parse(localStorage.getItem("students")) || [];
        renderStudentTable(table, students);
      });
  } else {
    let students = JSON.parse(localStorage.getItem("students")) || [];
    renderStudentTable(table, students);
  }
}

function renderStudentTable(table, students) {
  table.innerHTML = "";
  students.forEach((student, index) => {
    let sId = student.student_id || student.id;
    table.innerHTML += `
      <tr>
        <td><strong>${sId}</strong></td>
        <td>${student.name}</td>
        <td>${student.department}</td>
        <td>${student.semester}</td>
        <td>${student.email}</td>
        <td>
          <button class="btn btn-danger btn-sm" onclick="deleteStudent('${sId}', ${index})">
            <i class="bi bi-trash"></i> Delete
          </button>
        </td>
      </tr>
    `;
  });
}

function deleteStudent(studentId, index) {
  if (confirm("Delete student " + studentId + "?")) {
    let apiUrl = getApiUrl("students.php");
    if (apiUrl) {
      fetch(apiUrl + "?student_id=" + encodeURIComponent(studentId), { method: "DELETE" })
        .then(() => displayStudents());
    } else {
      let students = JSON.parse(localStorage.getItem("students")) || [];
      students.splice(index, 1);
      localStorage.setItem("students", JSON.stringify(students));
      displayStudents();
    }
  }
}

// ==========================
// COURSE CRUD & REGISTRATION OFFER (MySQL)
// ==========================

function addCourse() {
  let code = document.getElementById("courseCode").value.trim();
  let name = document.getElementById("courseName").value.trim();
  let credits = parseFloat(document.getElementById("courseCredits")?.value || "3.0");
  let faculty = document.getElementById("courseFaculty").value.trim();
  let semester = document.getElementById("courseSemester").value;
  let room = document.getElementById("courseRoom").value.trim();
  let day = document.getElementById("courseDay").value;
  let time = document.getElementById("courseTime").value.trim();
  let isOffered = document.getElementById("courseIsOffered")?.value || "Yes";

  if (code === "" || name === "") {
    alert("Please fill required course code and name");
    return;
  }

  let apiUrl = getApiUrl("courses.php");

  if (apiUrl) {
    fetch(apiUrl, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ code, name, credits, faculty, semester, room, day, time, isOffered })
    })
      .then(res => res.json())
      .then(data => {
        alert(data.message || "Course saved to database");
        displayCourses();
        document.getElementById("courseForm").reset();
      });
  } else {
    let courses = JSON.parse(localStorage.getItem("courses")) || [];
    courses.push({ code, name, credits, faculty, semester, room, day, time, isOffered });
    localStorage.setItem("courses", JSON.stringify(courses));
    displayCourses();
    document.getElementById("courseForm").reset();
    alert("Course Saved Locally");
  }
}

function displayCourses() {
  let table = document.getElementById("courseTableBody");
  if (!table) return;

  let apiUrl = getApiUrl("courses.php");

  if (apiUrl) {
    fetch(apiUrl)
      .then(res => res.json())
      .then(courses => renderCourseTable(table, courses))
      .catch(() => renderCourseTable(table, JSON.parse(localStorage.getItem("courses")) || []));
  } else {
    renderCourseTable(table, JSON.parse(localStorage.getItem("courses")) || []);
  }
}

function renderCourseTable(table, courses) {
  table.innerHTML = "";
  courses.forEach(function (course) {
    let statusBadge = (course.isOffered === "No" || course.is_offered === "No")
      ? '<span class="badge bg-secondary">Closed</span>' 
      : '<span class="badge bg-success">Open for Reg</span>';

    table.innerHTML += `
        <tr>
            <td><strong>${course.code || course.course_code}</strong></td>
            <td>${course.name || course.course_title}</td>
            <td><span class="badge bg-info text-dark">${course.credits || 3.0} Credits</span></td>
            <td>${course.faculty || course.faculty_name || 'Unassigned'}</td>
            <td>${course.semester}</td>
            <td>${course.day || ''} (${course.time || course.time_slot || 'TBA'}) - Room ${course.room || course.room_no || 'TBA'}</td>
            <td>${statusBadge}</td>
            <td>
                <button class="btn btn-danger btn-sm" onclick="deleteCourse('${course.code || course.course_code}')">
                  <i class="bi bi-trash"></i> Delete
                </button>
            </td>
        </tr>
    `;
  });
}

function deleteCourse(code) {
  if (confirm("Delete course " + code + "?")) {
    let apiUrl = getApiUrl("courses.php");
    if (apiUrl) {
      fetch(apiUrl + "?code=" + encodeURIComponent(code), { method: "DELETE" })
        .then(() => displayCourses());
    } else {
      let courses = JSON.parse(localStorage.getItem("courses")) || [];
      courses = courses.filter(c => c.code !== code);
      localStorage.setItem("courses", JSON.stringify(courses));
      displayCourses();
    }
  }
}

// ==========================
// NOTICE BOARD (MySQL)
// ==========================

function addNotice() {
  let title = document.getElementById("noticeTitle").value.trim();
  let category = document.getElementById("noticeCategory").value;
  let date = document.getElementById("noticeDate").value;
  let content = document.getElementById("noticeContent").value.trim();

  if (title === "" || content === "") {
    alert("Please enter title and content");
    return;
  }

  let apiUrl = getApiUrl("notices.php");

  if (apiUrl) {
    fetch(apiUrl, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ title, category, date, content })
    })
      .then(res => res.json())
      .then(data => {
        alert(data.message || "Notice Published to Database");
        displayAdminNotices();
        document.getElementById("noticeForm").reset();
      });
  } else {
    let notices = JSON.parse(localStorage.getItem("notices")) || [];
    notices.unshift({ title, category, date: date || new Date().toISOString().split("T")[0], content });
    localStorage.setItem("notices", JSON.stringify(notices));
    displayAdminNotices();
    document.getElementById("noticeForm").reset();
    alert("Notice Published Locally");
  }
}

function displayAdminNotices() {
  let table = document.getElementById("noticeTableBody");
  if (!table) return;

  let apiUrl = getApiUrl("notices.php");

  if (apiUrl) {
    fetch(apiUrl)
      .then(res => res.json())
      .then(notices => renderNoticeTable(table, notices))
      .catch(() => renderNoticeTable(table, JSON.parse(localStorage.getItem("notices")) || []));
  } else {
    renderNoticeTable(table, JSON.parse(localStorage.getItem("notices")) || []);
  }
}

function renderNoticeTable(table, notices) {
  table.innerHTML = "";
  notices.forEach((n) => {
    table.innerHTML += `
      <tr>
        <td><small class="fw-bold">${n.date || n.notice_date}</small></td>
        <td><strong>${n.title}</strong></td>
        <td><span class="badge bg-primary">${n.category}</span></td>
        <td><small>${n.content.substring(0, 100)}...</small></td>
        <td>
          <button class="btn btn-danger btn-sm" onclick="deleteNotice(${n.id})">
            <i class="bi bi-trash"></i> Delete
          </button>
        </td>
      </tr>
    `;
  });
}

function deleteNotice(id) {
  if (confirm("Delete notice?")) {
    let apiUrl = getApiUrl("notices.php");
    if (apiUrl) {
      fetch(apiUrl + "?id=" + id, { method: "DELETE" }).then(() => displayAdminNotices());
    } else {
      let notices = JSON.parse(localStorage.getItem("notices")) || [];
      notices.splice(id, 1);
      localStorage.setItem("notices", JSON.stringify(notices));
      displayAdminNotices();
    }
  }
}

// ==========================
// ROUTINE MANAGEMENT (MySQL)
// ==========================

function addClassRoutine() {
  let course = document.getElementById("crCourse").value.trim();
  let faculty = document.getElementById("crFaculty").value.trim();
  let semester = document.getElementById("crSemester").value;
  let day = document.getElementById("crDay").value;
  let time = document.getElementById("crTime").value.trim();
  let room = document.getElementById("crRoom").value.trim();

  if (course === "" || time === "") {
    alert("Please fill Course and Time");
    return;
  }

  let apiUrl = getApiUrl("routines.php");
  if (apiUrl) {
    fetch(apiUrl, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ routine_type: "Class", course, faculty, semester, day, time, room })
    }).then(res => res.json()).then(data => {
      alert(data.message);
      displayClassRoutines();
      document.getElementById("classRoutineForm").reset();
    });
  }
}

function displayClassRoutines() {
  let table = document.getElementById("classRoutineTableBody");
  if (!table) return;
  let apiUrl = getApiUrl("routines.php");
  if (apiUrl) {
    fetch(apiUrl + "?type=Class")
      .then(res => res.json())
      .then(routines => {
        table.innerHTML = "";
        routines.forEach((cr) => {
          table.innerHTML += `
            <tr>
              <td><span class="badge bg-dark">${cr.day}</span></td>
              <td>${cr.time}</td>
              <td><strong>${cr.course}</strong></td>
              <td>${cr.faculty}</td>
              <td>${cr.semester}</td>
              <td>${cr.room}</td>
              <td><button class="btn btn-danger btn-sm" onclick="deleteRoutine(${cr.id})"><i class="bi bi-trash"></i> Delete</button></td>
            </tr>
          `;
        });
      });
  }
}

function addExamRoutine() {
  let course = document.getElementById("erCourse").value.trim();
  let date = document.getElementById("erDate").value;
  let time = document.getElementById("erTime").value.trim();
  let room = document.getElementById("erRoom").value.trim();
  let semester = document.getElementById("erSemester").value.trim();

  let apiUrl = getApiUrl("routines.php");
  if (apiUrl) {
    fetch(apiUrl, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ routine_type: "Exam", course, date, time, room, semester })
    }).then(res => res.json()).then(data => {
      alert(data.message);
      displayExamRoutines();
      document.getElementById("examRoutineForm").reset();
    });
  }
}

function displayExamRoutines() {
  let table = document.getElementById("examRoutineTableBody");
  if (!table) return;
  let apiUrl = getApiUrl("routines.php");
  if (apiUrl) {
    fetch(apiUrl + "?type=Exam")
      .then(res => res.json())
      .then(routines => {
        table.innerHTML = "";
        routines.forEach((er) => {
          table.innerHTML += `
            <tr>
              <td><span class="badge bg-danger">${er.day || er.date}</span></td>
              <td>${er.time}</td>
              <td><strong>${er.course}</strong></td>
              <td>${er.semester}</td>
              <td>${er.room}</td>
              <td><button class="btn btn-danger btn-sm" onclick="deleteRoutine(${er.id})"><i class="bi bi-trash"></i> Delete</button></td>
            </tr>
          `;
        });
      });
  }
}

function deleteRoutine(id) {
  if (confirm("Delete routine slot?")) {
    let apiUrl = getApiUrl("routines.php");
    if (apiUrl) {
      fetch(apiUrl + "?id=" + id, { method: "DELETE" }).then(() => {
        displayClassRoutines();
        displayExamRoutines();
      });
    }
  }
}

// ==========================
// FEE TRANSACTIONS & APPROVALS (MySQL)
// ==========================

function addFee() {
  let id = document.getElementById("feeId").value.trim();
  let name = document.getElementById("feeName").value.trim();
  let department = document.getElementById("feeDepartment").value.trim();
  let semester = document.getElementById("feeSemester").value.trim();
  let amount = document.getElementById("feeAmount").value.trim();

  if (id === "" || name === "" || amount === "") {
    alert("Please fill all required fee details");
    return;
  }

  let apiUrl = getApiUrl("fees.php");
  if (apiUrl) {
    fetch(apiUrl, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id, name, department, semester, amount: parseInt(amount), details: "Manual Fee Invoice", status: "Pending" })
    }).then(res => res.json()).then(data => {
      alert(data.message);
      displayFees();
      document.getElementById("feeForm").reset();
    });
  }
}

function displayFees() {
  let table = document.getElementById("feeTableBody");
  if (!table) return;

  let apiUrl = getApiUrl("fees.php");
  if (apiUrl) {
    fetch(apiUrl)
      .then(res => res.json())
      .then(fees => renderFeeTable(table, fees))
      .catch(() => renderFeeTable(table, JSON.parse(localStorage.getItem("fees")) || []));
  } else {
    renderFeeTable(table, JSON.parse(localStorage.getItem("fees")) || []);
  }
}

function renderFeeTable(table, fees) {
  table.innerHTML = "";
  fees.forEach((fee) => {
    let statusBadge = fee.status === "Approved"
      ? '<span class="badge bg-success">Approved</span>'
      : fee.status === "Rejected"
      ? '<span class="badge bg-danger">Rejected</span>'
      : '<span class="badge bg-warning text-dark">Pending</span>';

    table.innerHTML += `
      <tr>
        <td><strong>${fee.student_id || fee.id}</strong></td>
        <td>${fee.name || fee.student_name}</td>
        <td>${fee.department || ''}</td>
        <td>${fee.semester || ''}</td>
        <td><small class="text-muted">${fee.details || 'Tuition Fee'}</small></td>
        <td><strong>${parseInt(fee.amount).toLocaleString()} BDT</strong></td>
        <td>${statusBadge}</td>
        <td>
          <button class="btn btn-success btn-sm me-1" onclick="updateFeeStatus(${fee.id}, 'Approved')">
            <i class="bi bi-check-circle"></i> Approve
          </button>
          <button class="btn btn-danger btn-sm" onclick="updateFeeStatus(${fee.id}, 'Rejected')">
            <i class="bi bi-x-circle"></i> Reject
          </button>
        </td>
      </tr>
    `;
  });
}

function updateFeeStatus(id, newStatus) {
  let apiUrl = getApiUrl("fees.php");
  if (apiUrl) {
    fetch(apiUrl, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id, status: newStatus })
    }).then(res => res.json()).then(data => {
      alert(data.message);
      displayFees();
    });
  }
}

// ==========================
// AUTHENTICATION & INITIALIZATION
// ==========================

function login() {
  let username = document.getElementById("username").value.trim();
  let password = document.getElementById("password").value.trim();

  if (username === "admin" && (password === "1234" || password !== "")) {
    localStorage.setItem("isLoggedIn", "true");
    if (window.location.protocol === "file:") {
      window.location.href = "dashboard.html";
    } else {
      window.location.href = "dashboard.php";
    }
  } else {
    alert("Invalid Username or Password. (Default: admin / 1234)");
  }
}

function logout() {
  localStorage.removeItem("isLoggedIn");
  if (window.location.protocol === "file:" || window.location.pathname.includes(".php")) {
    let target = window.location.pathname.includes("/pages/") ? "../../home.html" : "../home.html";
    window.location.href = target;
  } else {
    window.location.href = "../logout.php";
  }
}

function checkLogin() {
  if (window.location.pathname.includes("dashboard.php") || window.location.pathname.includes("dashboard.html")) {
    let loginStatus = localStorage.getItem("isLoggedIn");
    if (loginStatus !== "true") {
      window.location.href = "index.html";
    }
  }
}

function updateClock() {
  let clock = document.getElementById("clock");
  if (clock) clock.innerHTML = new Date().toLocaleTimeString();
}
setInterval(updateClock, 1000);

window.onload = function () {
  checkLogin();
  displayStudents();
  displayCourses();
  displayFees();
  displayAdminNotices();
  displayClassRoutines();
  displayExamRoutines();
  updateClock();
};


