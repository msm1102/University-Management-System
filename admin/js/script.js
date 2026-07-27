console.log("JS Connected Successfully");

// ==========================
// DASHBOARD COUNTS
// ==========================

function updateDashboardCounts() {

    let students =
        JSON.parse(localStorage.getItem("students")) || [];

    let faculties =
        JSON.parse(localStorage.getItem("faculties")) || [];

    let courses =
        JSON.parse(localStorage.getItem("courses")) || [];

    let fees =
        JSON.parse(localStorage.getItem("fees")) || [];

    let pendingFees =
        fees.filter(fee => fee.status === "Pending");

    let studentCard =
        document.getElementById("totalStudents");

    let facultyCard =
        document.getElementById("totalFaculty");

    let courseCard =
        document.getElementById("totalCourses");

    let feeCard =
        document.getElementById("pendingFees");

    if (studentCard)
        studentCard.innerHTML = students.length;

    if (facultyCard)
        facultyCard.innerHTML = faculties.length;

    if (courseCard)
        courseCard.innerHTML = courses.length;

    if (feeCard)
        feeCard.innerHTML = pendingFees.length;
}

// ==========================
// LIVE CLOCK
// ==========================

function updateClock() {

    let clock =
        document.getElementById("clock");

    if (!clock) return;

    let now = new Date();

    clock.innerHTML =
        now.toLocaleTimeString();
}

setInterval(updateClock, 1000);

// ==========================
// STUDENT CRUD
// ==========================

function addStudent() {

    let id =
        document.getElementById("studentId").value;

    let name =
        document.getElementById("studentName").value;

    let email =
        document.getElementById("studentEmail").value;

    let phone =
        document.getElementById("studentPhone").value;

    let department =
        document.getElementById("studentDepartment").value;

    let semester =
        document.getElementById("studentSemester").value;

    if (id === "" || name === "" || email === "") {

        alert("Please fill all fields");
        return;
    }

    let students =
        JSON.parse(localStorage.getItem("students"))
        || [];

    let editIndex =
        localStorage.getItem("studentEditIndex");

    let student = {

        id,
        name,
        email,
        phone,
        department,
        semester

    };

    if (editIndex !== null) {

        students[editIndex] = student;

        localStorage.removeItem(
            "studentEditIndex"
        );

        alert("Student Updated");

    } else {

        students.push(student);

        alert("Student Added");

    }

    localStorage.setItem(
        "students",
        JSON.stringify(students)
    );

    displayStudents();

    updateDashboardCounts();

    document
        .getElementById("studentForm")
        .reset();
}

function displayStudents() {

    let table =
        document.getElementById(
            "studentTableBody"
        );

    if (!table) return;

    let students =
        JSON.parse(
            localStorage.getItem("students")
        ) || [];

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

    let students =
        JSON.parse(
            localStorage.getItem("students")
        ) || [];

    let student =
        students[index];

    document.getElementById("studentId").value =
        student.id;

    document.getElementById("studentName").value =
        student.name;

    document.getElementById("studentEmail").value =
        student.email;

    document.getElementById("studentPhone").value =
        student.phone;

    document.getElementById("studentDepartment").value =
        student.department;

    document.getElementById("studentSemester").value =
        student.semester;

    localStorage.setItem(
        "studentEditIndex",
        index
    );
}

function deleteStudent(index) {

    if (confirm("Delete Student?")) {

        let students =
            JSON.parse(
                localStorage.getItem("students")
            ) || [];

        students.splice(index, 1);

        localStorage.setItem(
            "students",
            JSON.stringify(students)
        );

        displayStudents();

        updateDashboardCounts();
    }
}

// ==========================
// FACULTY CRUD
// ==========================

function addFaculty() {

    let id =
        document.getElementById("facultyId").value;

    let name =
        document.getElementById("facultyName").value;

    let email =
        document.getElementById("facultyEmail").value;

    let phone =
        document.getElementById("facultyPhone").value;

    let department =
        document.getElementById("facultyDepartment").value;

    let designation =
        document.getElementById("facultyDesignation").value;

    if (id === "" || name === "" || email === "") {

        alert("Please fill all fields");
        return;
    }

    let faculties =
        JSON.parse(
            localStorage.getItem("faculties")
        ) || [];

    let editIndex =
        localStorage.getItem(
            "facultyEditIndex"
        );

    let faculty = {

        id,
        name,
        email,
        phone,
        department,
        designation

    };

    if (editIndex !== null) {

        faculties[editIndex] = faculty;

        localStorage.removeItem(
            "facultyEditIndex"
        );

        alert("Faculty Updated");

    } else {

        faculties.push(faculty);

        alert("Faculty Added");

    }

    localStorage.setItem(
        "faculties",
        JSON.stringify(faculties)
    );

    displayFaculties();

    updateDashboardCounts();

    document
        .getElementById("facultyForm")
        .reset();
}

function displayFaculties() {

    let table =
        document.getElementById(
            "facultyTableBody"
        );

    if (!table) return;

    let faculties =
        JSON.parse(
            localStorage.getItem("faculties")
        ) || [];

    table.innerHTML = "";

    faculties.forEach(function (faculty, index) {

        table.innerHTML += `

        <tr>

            <td>${faculty.id}</td>
            <td>${faculty.name}</td>
            <td>${faculty.department}</td>
            <td>${faculty.designation}</td>
            <td>${faculty.email}</td>

            <td>

                <button
                class="btn btn-warning btn-sm"
                onclick="editFaculty(${index})">

                Edit

                </button>

                <button
                class="btn btn-danger btn-sm"
                onclick="deleteFaculty(${index})">

                Delete

                </button>

            </td>

        </tr>

        `;
    });
}

function editFaculty(index) {

    let faculties =
        JSON.parse(
            localStorage.getItem("faculties")
        ) || [];

    let faculty =
        faculties[index];

    document.getElementById("facultyId").value =
        faculty.id;

    document.getElementById("facultyName").value =
        faculty.name;

    document.getElementById("facultyEmail").value =
        faculty.email;

    document.getElementById("facultyPhone").value =
        faculty.phone;

    document.getElementById("facultyDepartment").value =
        faculty.department;

    document.getElementById("facultyDesignation").value =
        faculty.designation;

    localStorage.setItem(
        "facultyEditIndex",
        index
    );
}

function deleteFaculty(index) {

    if (confirm("Delete Faculty?")) {

        let faculties =
            JSON.parse(
                localStorage.getItem("faculties")
            ) || [];

        faculties.splice(index, 1);

        localStorage.setItem(
            "faculties",
            JSON.stringify(faculties)
        );

        displayFaculties();

        updateDashboardCounts();
    }
}

// ==========================
// COURSE CRUD
// ==========================

function addCourse() {

    let code = document.getElementById("courseCode").value;
    let name = document.getElementById("courseName").value;
    let faculty = document.getElementById("courseFaculty").value;
    let semester = document.getElementById("courseSemester").value;
    let room = document.getElementById("courseRoom").value;
    let day = document.getElementById("courseDay").value;
    let time = document.getElementById("courseTime").value;

    if (code === "" || name === "") {
        alert("Please fill all fields");
        return;
    }

    let courses =
        JSON.parse(localStorage.getItem("courses")) || [];

    let editIndex =
        localStorage.getItem("courseEditIndex");

    let course = {
        code,
        name,
        faculty,
        semester,
        room,
        day,
        time
    };

    if (editIndex !== null) {

        courses[editIndex] = course;

        localStorage.removeItem("courseEditIndex");

        alert("Course Updated");

    } else {

        courses.push(course);

        alert("Course Added");

    }

    localStorage.setItem(
        "courses",
        JSON.stringify(courses)
    );

    displayCourses();

    updateDashboardCounts();

    document.getElementById("courseForm").reset();
}

function displayCourses() {

    let table =
        document.getElementById("courseTableBody");

    if (!table) return;

    let courses =
        JSON.parse(localStorage.getItem("courses"))
        || [];

    table.innerHTML = "";

    courses.forEach(function(course, index){

        table.innerHTML += `

        <tr>

            <td>${course.code}</td>
            <td>${course.name}</td>
            <td>${course.faculty}</td>
            <td>${course.semester}</td>
            <td>${course.room}</td>
            <td>${course.day}</td>
            <td>${course.time}</td>

            <td>

                <button
                class="btn btn-warning btn-sm"
                onclick="editCourse(${index})">

                Edit

                </button>

                <button
                class="btn btn-danger btn-sm"
                onclick="deleteCourse(${index})">

                Delete

                </button>

            </td>

        </tr>

        `;
    });

}

function editCourse(index){

    let courses =
        JSON.parse(localStorage.getItem("courses"))
        || [];

    let course = courses[index];

    document.getElementById("courseCode").value =
        course.code;

    document.getElementById("courseName").value =
        course.name;

    document.getElementById("courseFaculty").value =
        course.faculty;

    document.getElementById("courseSemester").value =
        course.semester;

    document.getElementById("courseRoom").value =
        course.room;

    document.getElementById("courseDay").value =
        course.day;

    document.getElementById("courseTime").value =
        course.time;

    localStorage.setItem(
        "courseEditIndex",
        index
    );
}

function deleteCourse(index){

    if(confirm("Delete Course?")){

        let courses =
            JSON.parse(localStorage.getItem("courses"))
            || [];

        courses.splice(index,1);

        localStorage.setItem(
            "courses",
            JSON.stringify(courses)
        );

        displayCourses();

        updateDashboardCounts();

    }

}

function searchCourse(){

    let input =
        document.getElementById("courseSearch");

    if(!input) return;

    let filter =
        input.value.toUpperCase();

    let rows =
        document.querySelectorAll(
            "#courseTableBody tr"
        );

    rows.forEach(function(row){

        let text =
            row.innerText.toUpperCase();

        row.style.display =
            text.includes(filter)
            ? ""
            : "none";

    });

}
// ==========================
// FEE CRUD
// ==========================

function addFee(){

    let id =
        document.getElementById("feeId").value;

    let name =
        document.getElementById("feeName").value;

    let department =
        document.getElementById("feeDepartment").value;

    let semester =
        document.getElementById("feeSemester").value;

    let amount =
        document.getElementById("feeAmount").value;

    let fees =
        JSON.parse(localStorage.getItem("fees"))
        || [];

    fees.push({
        id,
        name,
        department,
        semester,
        amount,
        status:"Pending"
    });

    localStorage.setItem(
        "fees",
        JSON.stringify(fees)
    );

    displayFees();

    updateDashboardCounts();

    document.getElementById("feeForm").reset();

    alert("Fee Added");
}

function displayFees(){

    let table =
        document.getElementById("feeTableBody");

    if(!table) return;

    let fees =
        JSON.parse(localStorage.getItem("fees"))
        || [];

    table.innerHTML = "";

    fees.forEach(function(fee,index){

        table.innerHTML += `

        <tr>

        <td>${fee.id}</td>
        <td>${fee.name}</td>
        <td>${fee.department}</td>
        <td>${fee.semester}</td>
        <td>${fee.amount} Tk</td>

        <td>

        <span class="badge ${
            fee.status==="Approved"
            ? "bg-success"
            : fee.status==="Rejected"
            ? "bg-danger"
            : "bg-warning"
        }">

        ${fee.status}

        </span>

        </td>

        <td>

        <button
        class="btn btn-success btn-sm"
        onclick="approveFee(${index})">

        Approve

        </button>

        <button
        class="btn btn-danger btn-sm"
        onclick="rejectFee(${index})">

        Reject

        </button>

        </td>

        </tr>

        `;
    });
}

function approveFee(index){

    let fees =
        JSON.parse(localStorage.getItem("fees"))
        || [];

    fees[index].status = "Approved";

    localStorage.setItem(
        "fees",
        JSON.stringify(fees)
    );

    displayFees();

    updateDashboardCounts();
}

function rejectFee(index){

    let fees =
        JSON.parse(localStorage.getItem("fees"))
        || [];

    fees[index].status = "Rejected";

    localStorage.setItem(
        "fees",
        JSON.stringify(fees)
    );

    displayFees();

    updateDashboardCounts();
}

function searchFee(){

    let input =
        document.getElementById("feeSearch");

    let filter =
        input.value.toUpperCase();

    let rows =
        document.querySelectorAll(
            "#feeTableBody tr"
        );

    rows.forEach(function(row){

        let text =
            row.innerText.toUpperCase();

        row.style.display =
            text.includes(filter)
            ? ""
            : "none";

    });

}
// ==========================
// GRADE CRUD
// ==========================

function addGrade() {

    let studentId =
        document.getElementById(
            "gradeStudentId"
        ).value;

    let studentName =
        document.getElementById(
            "gradeStudentName"
        ).value;

    let course =
        document.getElementById(
            "gradeCourse"
        ).value;

    let semester =
        document.getElementById(
            "gradeSemester"
        ).value;

    let grade =
        document.getElementById(
            "gradeGrade"
        ).value;

    let cgpa =
        document.getElementById(
            "gradeCgpa"
        ).value;

    if (
        studentId === "" ||
        studentName === "" ||
        course === ""
    ) {

        alert("Fill all fields");
        return;
    }

    let grades =
        JSON.parse(
            localStorage.getItem("grades")
        ) || [];

    grades.push({
        studentId,
        studentName,
        course,
        semester,
        grade,
        cgpa
    });

    localStorage.setItem(
        "grades",
        JSON.stringify(grades)
    );

    displayGrades();

    document
        .getElementById("gradeForm")
        .reset();

    alert("Grade Published");
}

function displayGrades() {

    let table =
        document.getElementById(
            "gradeTableBody"
        );

    if (!table) return;

    let grades =
        JSON.parse(
            localStorage.getItem("grades")
        ) || [];

    table.innerHTML = "";

    grades.forEach(function(item,index){

        table.innerHTML += `

        <tr>

        <td>${item.studentId}</td>
        <td>${item.studentName}</td>
        <td>${item.course}</td>
        <td>${item.semester}</td>
        <td>${item.grade}</td>
        <td>${item.cgpa}</td>

        <td>

        <span class="badge bg-success">
        Published
        </span>

        </td>

        <td>

        <button
        class="btn btn-danger btn-sm"
        onclick="deleteGrade(${index})">

        Delete

        </button>

        </td>

        </tr>

        `;
    });
}

function deleteGrade(index){

    if(confirm("Delete Grade?")){

        let grades =
            JSON.parse(
                localStorage.getItem("grades")
            ) || [];

        grades.splice(index,1);

        localStorage.setItem(
            "grades",
            JSON.stringify(grades)
        );

        displayGrades();
    }
}

function searchGrade(){

    let input =
        document.getElementById(
            "gradeSearch"
        );

    let filter =
        input.value.toUpperCase();

    let rows =
        document.querySelectorAll(
            "#gradeTableBody tr"
        );

    rows.forEach(function(row){

        let text =
            row.innerText.toUpperCase();

        row.style.display =
            text.includes(filter)
            ? ""
            : "none";
    });
}
// ==========================
// REPORTS
// ==========================

function loadReports(){

    let students =
    JSON.parse(localStorage.getItem("students")) || [];

    let faculties =
    JSON.parse(localStorage.getItem("faculties")) || [];

    let courses =
    JSON.parse(localStorage.getItem("courses")) || [];

    let fees =
    JSON.parse(localStorage.getItem("fees")) || [];

    let grades =
    JSON.parse(localStorage.getItem("grades")) || [];

    let reportStudents =
    document.getElementById("reportStudents");

    let reportFaculty =
    document.getElementById("reportFaculty");

    let reportCourses =
    document.getElementById("reportCourses");

    let reportGrades =
    document.getElementById("reportGrades");

    if(reportStudents)
        reportStudents.innerHTML = students.length;

    if(reportFaculty)
        reportFaculty.innerHTML = faculties.length;

    if(reportCourses)
        reportCourses.innerHTML = courses.length;

    if(reportGrades)
        reportGrades.innerHTML = grades.length;

    let s1 =
    document.getElementById("summaryStudents");

    let s2 =
    document.getElementById("summaryFaculty");

    let s3 =
    document.getElementById("summaryCourses");

    let s4 =
    document.getElementById("summaryFees");

    let s5 =
    document.getElementById("summaryGrades");

    if(s1) s1.innerHTML = students.length;
    if(s2) s2.innerHTML = faculties.length;
    if(s3) s3.innerHTML = courses.length;
    if(s4) s4.innerHTML = fees.length;
    if(s5) s5.innerHTML = grades.length;
}
// ==========================
// SETTINGS
// ==========================

function saveSettings() {

    let adminName =
        document.getElementById("adminName").value;

    let adminPassword =
        document.getElementById("adminPassword").value;

    localStorage.setItem(
        "adminName",
        adminName
    );

    localStorage.setItem(
        "adminPassword",
        adminPassword
    );

    alert("Settings Saved Successfully");
}

function loadSettings() {

    let adminName =
        localStorage.getItem("adminName");

    let adminPassword =
        localStorage.getItem("adminPassword");

    let nameField =
        document.getElementById("adminName");

    let passwordField =
        document.getElementById("adminPassword");

    if (nameField) {

        nameField.value =
            adminName || "";

    }

    if (passwordField) {

        passwordField.value =
            adminPassword || "";

    }
}

function resetSystem() {

    let result = confirm(
        "Are you sure? All data will be deleted!"
    );

    if (result) {

        localStorage.clear();

        alert("System Reset Successful");

        location.reload();
    }
}


// ==========================
// GRADES
// ==========================

function publishGrade() {

    alert(
        "Semester Grade Published Successfully!"
    );
}
// ==========================
// STUDENT SEARCH
// ==========================

function searchStudent() {

    let keyword =
        document.getElementById("studentSearch")
        .value
        .toLowerCase();

    let students =
        JSON.parse(localStorage.getItem("students"))
        || [];

    let table =
        document.getElementById("studentTableBody");

    if (!table) return;

    table.innerHTML = "";

    students
        .filter(student =>
            student.name.toLowerCase().includes(keyword) ||
            student.id.toLowerCase().includes(keyword)
        )
        .forEach((student, index) => {

            table.innerHTML += `
            <tr>
                <td>${student.id}</td>
                <td>${student.name}</td>
                <td>${student.department}</td>
                <td>${student.semester}</td>
                <td>${student.email}</td>
                <td>
                    <button class="btn btn-warning btn-sm"
                    onclick="editStudent(${index})">
                    Edit
                    </button>

                    <button class="btn btn-danger btn-sm"
                    onclick="deleteStudent(${index})">
                    Delete
                    </button>
                </td>
            </tr>
            `;
        });
}

// ==========================
// FACULTY SEARCH
// ==========================

function searchFaculty() {

    let keyword =
        document.getElementById("facultySearch")
        .value
        .toLowerCase();

    let faculties =
        JSON.parse(localStorage.getItem("faculties"))
        || [];

    let table =
        document.getElementById("facultyTableBody");

    if (!table) return;

    table.innerHTML = "";

    faculties
        .filter(faculty =>
            faculty.name.toLowerCase().includes(keyword) ||
            faculty.id.toLowerCase().includes(keyword)
        )
        .forEach((faculty, index) => {

            table.innerHTML += `
            <tr>
                <td>${faculty.id}</td>
                <td>${faculty.name}</td>
                <td>${faculty.department}</td>
                <td>${faculty.designation}</td>
                <td>${faculty.email}</td>
                <td>
                    <button class="btn btn-warning btn-sm"
                    onclick="editFaculty(${index})">
                    Edit
                    </button>

                    <button class="btn btn-danger btn-sm"
                    onclick="deleteFaculty(${index})">
                    Delete
                    </button>
                </td>
            </tr>
            `;
        });
}

// ==========================
// COURSE SEARCH
// ==========================

function searchCourse() {

    let keyword =
        document.getElementById("courseSearch")
        .value
        .toLowerCase();

    let courses =
        JSON.parse(localStorage.getItem("courses"))
        || [];

    let table =
        document.getElementById("courseTableBody");

    if (!table) return;

    table.innerHTML = "";

    courses
        .filter(course =>
            course.name.toLowerCase().includes(keyword) ||
            course.code.toLowerCase().includes(keyword)
        )
        .forEach((course, index) => {

            table.innerHTML += `
            <tr>
                <td>${course.code}</td>
                <td>${course.name}</td>
                <td>${course.faculty}</td>
                <td>${course.semester}</td>
                <td>${course.room}</td>
                <td>${course.day}</td>
                <td>${course.time}</td>
                <td>
                    <button class="btn btn-warning btn-sm"
                    onclick="editCourse(${index})">
                    Edit
                    </button>

                    <button class="btn btn-danger btn-sm"
                    onclick="deleteCourse(${index})">
                    Delete
                    </button>
                </td>
            </tr>
            `;
        });
}
// ==========================
// LOGIN SYSTEM
// ==========================

function login() {

    let username =
        document.getElementById("username").value;

    let password =
        document.getElementById("password").value;

    let savedPassword =
        localStorage.getItem("adminPassword") || "1234";

    if (
        username === "admin" &&
        password === savedPassword
    ) {

        localStorage.setItem(
            "isLoggedIn",
            "true"
        );

        window.location.href =
            "dashboard.html";

    } else {

        alert("Invalid Username or Password");

    }
}

function logout() {

    localStorage.removeItem(
        "isLoggedIn"
    );

    window.location.href =
        "index.html";
}

function checkLogin() {

    if (
        window.location.pathname.includes(
            "dashboard.html"
        )
    ) {

        let loginStatus =
            localStorage.getItem(
                "isLoggedIn"
            );

        if (
            loginStatus !== "true"
        ) {

            window.location.href =
                "index.html";
        }
    }
}

function loadAdminName() {

    let adminName =
        localStorage.getItem(
            "adminName"
        ) || "Admin";

    let welcome =
        document.getElementById(
            "welcomeAdmin"
        );

    if (welcome) {

        welcome.innerHTML =
            "Welcome " + adminName;
    }
}

// ==========================
// PAGE LOAD
// ==========================

window.onload = function(){

    checkLogin();

    displayStudents();

    displayFaculties();

    displayCourses();

    displayFees();

    displayGrades();

    updateDashboardCounts();

    updateClock();

    loadReports();

    loadSettings();

    loadAdminName();

};