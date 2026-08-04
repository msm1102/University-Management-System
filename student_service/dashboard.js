// ==========================================
// STUDENT PORTAL MYSQL & API INTEGRATION LOGIC
// ==========================================

const TUITION_RATE_PER_CREDIT = 3000; // 1 Credit = 3000 BDT
const CURRENT_STUDENT_ID = "2024-001";

function getApiEndpoint(file) {
  if (window.location.protocol === "file:") return null;
  return "../api/" + file;
}

// 1. Fetch Master Offered Courses
function fetchOfferedCourses(callback) {
  let endpoint = getApiEndpoint("courses.php");
  if (endpoint) {
    fetch(endpoint)
      .then((res) => res.json())
      .then((data) => callback(data))
      .catch(() => callback(getLocalCourses()));
  } else {
    callback(getLocalCourses());
  }
}

function getLocalCourses() {
  return JSON.parse(localStorage.getItem("courses")) || [
    { code: "CSE-301", name: "Database Management Systems", credits: 3.0, faculty: "Prof. Md. Ahsan", semester: "Spring", room: "Lab 201", day: "Sunday", time: "10:00 AM - 11:30 AM", isOffered: "Yes" },
    { code: "CSE-305", name: "Software Engineering & Patterns", credits: 3.0, faculty: "Dr. Farhana Yasmin", semester: "Spring", room: "Room 405", day: "Tuesday", time: "11:30 AM - 01:00 PM", isOffered: "Yes" },
    { code: "CSE-401", name: "Artificial Intelligence", credits: 3.0, faculty: "Dr. Kamrul Hasan", semester: "Fall", room: "Lab 304", day: "Monday", time: "09:00 AM - 10:30 AM", isOffered: "Yes" },
    { code: "MATH-201", name: "Linear Algebra & Differential Equations", credits: 3.0, faculty: "Prof. S. R. Chowdhury", semester: "Spring", room: "Room 102", day: "Wednesday", time: "02:00 PM - 03:30 PM", isOffered: "Yes" }
  ];
}

// 2. Fetch Student Enrolled Courses
function fetchRegisteredCourses(callback) {
  let endpoint = getApiEndpoint("student_registration.php");
  if (endpoint) {
    fetch(endpoint + "?student_id=" + CURRENT_STUDENT_ID)
      .then((res) => res.json())
      .then((data) => callback(data))
      .catch(() => callback(getLocalRegistered()));
  } else {
    callback(getLocalRegistered());
  }
}

function getLocalRegistered() {
  let reg = JSON.parse(localStorage.getItem("studentRegisteredCourses"));
  if (!reg) {
    reg = getLocalCourses().slice(0, 3);
    localStorage.setItem("studentRegisteredCourses", JSON.stringify(reg));
  }
  return reg;
}

// ==========================================
// COURSE REGISTRATION RENDER
// ==========================================

function renderCourseRegistration() {
  let availableTbody = document.getElementById("availableCoursesTbody");
  let myTbody = document.getElementById("myCoursesTbody");
  let creditHeader = document.getElementById("studentTotalCreditsHeader");

  if (!availableTbody || !myTbody) return;

  fetchOfferedCourses((allCourses) => {
    fetchRegisteredCourses((myReg) => {
      let totalCredits = myReg.reduce((sum, c) => sum + (parseFloat(c.credits) || 3.0), 0);

      if (creditHeader) creditHeader.innerHTML = `${totalCredits.toFixed(1)} Credits`;

      // Available Courses
      availableTbody.innerHTML = "";
      let offered = allCourses.filter((c) => (c.isOffered || c.is_offered) !== "No");

      offered.forEach((c) => {
        let code = c.code || c.course_code;
        let name = c.name || c.course_title;
        let credits = parseFloat(c.credits) || 3.0;
        let isAlreadyReg = myReg.some((r) => (r.code || r.course_code) === code);
        let cost = (credits * TUITION_RATE_PER_CREDIT).toLocaleString();

        availableTbody.innerHTML += `
          <tr style="border-bottom: 1px solid #f1f5f9;">
            <td style="padding: 12px;"><strong>${code}</strong></td>
            <td style="padding: 12px;">${name}</td>
            <td style="padding: 12px;"><span style="background: #e0f2fe; color: #0369a1; padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 13px;">${credits} Credits</span></td>
            <td style="padding: 12px;">${c.faculty || c.faculty_name || 'Faculty Member'}</td>
            <td style="padding: 12px;">${c.day || ''} (${c.time || c.time_slot || 'TBA'}) - ${c.room || c.room_no || 'TBA'}</td>
            <td style="padding: 12px;"><strong>${cost} BDT</strong></td>
            <td style="padding: 12px;">
              ${
                isAlreadyReg
                  ? `<button disabled style="background: #cbd5e1; color: #475569; border: none; padding: 6px 12px; border-radius: 6px; font-weight: 600;"><i class="fa-solid fa-check me-1"></i> Enrolled</button>`
                  : `<button onclick="registerCourse('${code}')" style="background: #2563eb; color: white; border: none; padding: 6px 14px; border-radius: 6px; font-weight: 600; cursor: pointer;"><i class="fa-solid fa-plus me-1"></i> Register</button>`
              }
            </td>
          </tr>
        `;
      });

      // Enrolled Courses
      myTbody.innerHTML = "";
      if (myReg.length === 0) {
        myTbody.innerHTML = `<tr><td colspan="7" style="padding: 20px; text-align: center; color: #64748b;">No courses registered yet.</td></tr>`;
      } else {
        myReg.forEach((c) => {
          let code = c.code || c.course_code;
          let name = c.name || c.course_title;
          let credits = parseFloat(c.credits) || 3.0;
          let cost = (credits * TUITION_RATE_PER_CREDIT).toLocaleString();

          myTbody.innerHTML += `
            <tr style="border-bottom: 1px solid #f1f5f9;">
              <td style="padding: 12px;"><strong>${code}</strong></td>
              <td style="padding: 12px;">${name}</td>
              <td style="padding: 12px;"><span style="background: #dcfce7; color: #15803d; padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 13px;">${credits} Credits</span></td>
              <td style="padding: 12px;">${c.faculty || c.faculty_name || 'Faculty'}</td>
              <td style="padding: 12px;"><strong>${cost} BDT</strong></td>
              <td style="padding: 12px;"><span style="background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Registered</span></td>
              <td style="padding: 12px;">
                <button onclick="dropCourse('${code}')" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-weight: 600; cursor: pointer;"><i class="fa-solid fa-trash me-1"></i> Drop</button>
              </td>
            </tr>
          `;
        });
      }
    });
  });
}

function registerCourse(code) {
  let endpoint = getApiEndpoint("student_registration.php");
  if (endpoint) {
    fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ student_id: CURRENT_STUDENT_ID, course_code: code })
    })
      .then((res) => res.json())
      .then((data) => {
        alert(data.message || "Enrolled!");
        renderCourseRegistration();
        renderStudentDashboardStats();
      });
  } else {
    let all = getLocalCourses();
    let target = all.find((c) => c.code === code);
    let myReg = getLocalRegistered();
    if (target) myReg.push(target);
    localStorage.setItem("studentRegisteredCourses", JSON.stringify(myReg));
    renderCourseRegistration();
    renderStudentDashboardStats();
    alert("Enrolled!");
  }
}

function dropCourse(code) {
  if (confirm("Drop course " + code + "?")) {
    let endpoint = getApiEndpoint("student_registration.php");
    if (endpoint) {
      fetch(endpoint, {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ student_id: CURRENT_STUDENT_ID, course_code: code })
      }).then(() => {
        renderCourseRegistration();
        renderStudentDashboardStats();
      });
    } else {
      let myReg = getLocalRegistered().filter((c) => c.code !== code);
      localStorage.setItem("studentRegisteredCourses", JSON.stringify(myReg));
      renderCourseRegistration();
      renderStudentDashboardStats();
    }
  }
}

// ==========================================
// FEE PAYMENT LOGIC (MySQL)
// ==========================================

function renderFeePaymentPage() {
  let feeCreditsEl = document.getElementById("studentFeeCredits");
  let totalFeeEl = document.getElementById("studentTotalFeeAmount");
  let paidStatusEl = document.getElementById("studentPaidStatus");
  let pendingFeeEl = document.getElementById("studentPendingFeeAmount");
  let historyTbody = document.getElementById("studentFeeHistoryTbody");

  if (!historyTbody) return;

  fetchRegisteredCourses((myReg) => {
    let totalCredits = myReg.reduce((sum, c) => sum + (parseFloat(c.credits) || 3.0), 0);
    let calculatedTuitionFee = totalCredits * TUITION_RATE_PER_CREDIT;

    if (feeCreditsEl) feeCreditsEl.innerHTML = `${totalCredits.toFixed(1)} Credits`;
    if (totalFeeEl) totalFeeEl.innerHTML = `${calculatedTuitionFee.toLocaleString()} BDT`;

    let endpoint = getApiEndpoint("fees.php");
    let fetchCall = endpoint ? fetch(endpoint + "?student_id=" + CURRENT_STUDENT_ID).then((r) => r.json()) : Promise.resolve(JSON.parse(localStorage.getItem("fees")) || []);

    fetchCall.then((fees) => {
      let approvedSum = 0;
      let pendingSum = 0;

      fees.forEach((f) => {
        if (f.status === "Approved") approvedSum += parseInt(f.amount || 0);
        if (f.status === "Pending") pendingSum += parseInt(f.amount || 0);
      });

      if (paidStatusEl) paidStatusEl.innerHTML = `${approvedSum.toLocaleString()} BDT Approved`;
      if (pendingFeeEl) pendingFeeEl.innerHTML = `${pendingSum.toLocaleString()} BDT Pending`;

      historyTbody.innerHTML = "";
      if (fees.length === 0) {
        historyTbody.innerHTML = `<tr><td colspan="4" style="padding: 15px; text-align: center; color: #64748b;">No payment records found.</td></tr>`;
      } else {
        fees.forEach((f) => {
          let statusBadge = f.status === "Approved"
            ? `<span style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 4px; font-weight: 600; font-size: 13px;">Approved</span>`
            : f.status === "Rejected"
            ? `<span style="background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 4px; font-weight: 600; font-size: 13px;">Rejected</span>`
            : `<span style="background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 4px; font-weight: 600; font-size: 13px;">Pending Approval</span>`;

          historyTbody.innerHTML += `
            <tr style="border-bottom: 1px solid #f1f5f9;">
              <td style="padding: 12px;">${f.date || new Date().toLocaleDateString()}</td>
              <td style="padding: 12px;"><strong>${f.details || 'Fee Invoice'}</strong></td>
              <td style="padding: 12px;"><strong>${parseInt(f.amount).toLocaleString()} BDT</strong></td>
              <td style="padding: 12px;">${statusBadge}</td>
            </tr>
          `;
        });
      }
    });
  });
}

function submitStudentPayment(e) {
  e.preventDefault();
  let method = document.getElementById("payMethod").value;
  let trxId = document.getElementById("payTrxId").value.trim();
  let amount = document.getElementById("payAmount").value.trim();

  if (!trxId || !amount) {
    alert("Please fill transaction ID and amount");
    return;
  }

  let endpoint = getApiEndpoint("fees.php");
  if (endpoint) {
    fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        id: CURRENT_STUDENT_ID,
        name: "Sakib Khan Rony",
        department: "CSE",
        semester: "7th Semester",
        amount: parseInt(amount),
        details: `${method} - TrxID: ${trxId}`,
        status: "Pending"
      })
    }).then(res => res.json()).then(data => {
      alert(data.message || "Payment submitted!");
      document.getElementById("studentPaymentForm").reset();
      renderFeePaymentPage();
    });
  } else {
    let fees = JSON.parse(localStorage.getItem("fees")) || [];
    fees.unshift({ id: CURRENT_STUDENT_ID, name: "Sakib Khan Rony", department: "CSE", semester: "7th Semester", amount: parseInt(amount), details: `${method} - TrxID: ${trxId}`, status: "Pending", date: new Date().toLocaleDateString() });
    localStorage.setItem("fees", JSON.stringify(fees));
    document.getElementById("studentPaymentForm").reset();
    renderFeePaymentPage();
    alert("Payment submitted!");
  }
}

// ==========================================
// NOTICE BOARD & ROUTINES (MySQL)
// ==========================================

function renderStudentNotices() {
  let noticeBoardContainer = document.getElementById("studentNoticeBoardContainer");
  let latestNoticeContainer = document.getElementById("latestNoticeContainer");

  let endpoint = getApiEndpoint("notices.php");
  let fetchCall = endpoint ? fetch(endpoint).then((r) => r.json()) : Promise.resolve(JSON.parse(localStorage.getItem("notices")) || []);

  fetchCall.then((notices) => {
    if (latestNoticeContainer && notices.length > 0) {
      let top = notices[0];
      latestNoticeContainer.innerHTML = `
        <h3 style="font-size: 16px; font-weight: 700; color: #1e3a8a; margin-bottom: 5px;">${top.title}</h3>
        <small style="color: #64748b; font-size: 12px; display: block; margin-bottom: 8px;"><i class="fa-regular fa-calendar me-1"></i> ${top.date || top.notice_date} | Category: ${top.category}</small>
        <p style="color: #475569; font-size: 14px;">${top.content}</p>
      `;
    }

    if (noticeBoardContainer) {
      noticeBoardContainer.innerHTML = "";
      notices.forEach((n) => {
        noticeBoardContainer.innerHTML += `
          <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border-left: 5px solid #2563eb;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
              <h2 style="font-size: 18px; font-weight: 700; color: #1e3a8a;"><i class="fa-solid fa-bullhorn" style="color: #d97706; margin-right: 8px;"></i>${n.title}</h2>
              <span style="background: #eff6ff; color: #1d4ed8; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">${n.category}</span>
            </div>
            <small style="color: #64748b; display: block; margin-bottom: 10px;"><i class="fa-regular fa-clock me-1"></i> Published Date: ${n.date || n.notice_date}</small>
            <p style="color: #334155; line-height: 1.6;">${n.content}</p>
          </div>
        `;
      });
    }
  });
}

function renderStudentRoutines() {
  let classTbody = document.getElementById("studentClassRoutineTbody");
  let examTbody = document.getElementById("studentExamRoutineTbody");

  if (!classTbody && !examTbody) return;

  let endpoint = getApiEndpoint("routines.php");
  if (endpoint) {
    if (classTbody) {
      fetch(endpoint + "?type=Class").then(r => r.json()).then(routines => {
        classTbody.innerHTML = "";
        routines.forEach(cr => {
          classTbody.innerHTML += `
            <tr style="border-bottom: 1px solid #f1f5f9;">
              <td style="padding: 12px;"><span style="background: #1e293b; color: white; padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 12px;">${cr.day}</span></td>
              <td style="padding: 12px;">${cr.time}</td>
              <td style="padding: 12px;"><strong>${cr.course}</strong></td>
              <td style="padding: 12px;">${cr.faculty}</td>
              <td style="padding: 12px;">${cr.semester}</td>
              <td style="padding: 12px;">${cr.room}</td>
            </tr>
          `;
        });
      });
    }

    if (examTbody) {
      fetch(endpoint + "?type=Exam").then(r => r.json()).then(routines => {
        examTbody.innerHTML = "";
        routines.forEach(er => {
          examTbody.innerHTML += `
            <tr style="border-bottom: 1px solid #f1f5f9;">
              <td style="padding: 12px;"><span style="background: #fee2e2; color: #b91c1c; padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 13px;">${er.day || er.date}</span></td>
              <td style="padding: 12px;">${er.time}</td>
              <td style="padding: 12px;"><strong>${er.course}</strong></td>
              <td style="padding: 12px;">${er.semester}</td>
              <td style="padding: 12px;">${er.room}</td>
            </tr>
          `;
        });
      });
    }
  }
}

function renderStudentDashboardStats() {
  let coursesEl = document.getElementById("dashStudentCourses");
  let creditsEl = document.getElementById("dashStudentCredits");
  let feeEl = document.getElementById("dashStudentFee");

  fetchRegisteredCourses((myReg) => {
    let totalCredits = myReg.reduce((sum, c) => sum + (parseFloat(c.credits) || 3.0), 0);
    let totalFee = totalCredits * TUITION_RATE_PER_CREDIT;

    if (coursesEl) coursesEl.innerHTML = `${myReg.length} Courses`;
    if (creditsEl) creditsEl.innerHTML = `${totalCredits.toFixed(1)} Credits`;
    if (feeEl) feeEl.innerHTML = `${totalFee.toLocaleString()} BDT`;
  });
}

document.addEventListener("DOMContentLoaded", function () {
  renderStudentDashboardStats();
  renderCourseRegistration();
  renderFeePaymentPage();
  renderStudentNotices();
  renderStudentRoutines();
});