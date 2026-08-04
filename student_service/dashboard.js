function StatsCardComponent(title, value, iconClass, colorClass) {
    return `
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition hover:shadow-md">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">${title}</h3>
                <div class="${colorClass} text-white p-3 rounded-lg flex items-center justify-center">
                    <i class="${iconClass} text-xl"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">${value}</p>
        </div>
    `;
}

const dashboardData = [
    { title: "Current Semester", value: "7th Semester", icon: "fa-solid fa-graduation-cap", color: "bg-blue-600" },
    { title: "Enrolled Courses", value: "5 Courses", icon: "fa-solid fa-book-open", color: "bg-green-600" },
    { title: "CGPA", value: "3.45", icon: "fa-solid fa-chart-line", color: "bg-purple-600" },
    { title: "Pending Fees", value: "0 BDT", icon: "fa-solid fa-wallet", color: "bg-amber-500" }
];

function renderDashboardCards() {
    const container = document.getElementById('stats-cards-container');
    if (!container) return;

    let innerContent = '';
    dashboardData.forEach(item => {
        innerContent += StatsCardComponent(item.title, item.value, item.icon, item.color);
    });

    container.innerHTML = innerContent;
}

document.addEventListener('DOMContentLoaded', renderDashboardCards);