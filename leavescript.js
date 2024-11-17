// Toggle sections visibility based on sidebar links
document.addEventListener('DOMContentLoaded', function () {
    const sectionLinks = document.querySelectorAll('.sidebar ul li a');
    const sections = document.querySelectorAll('.main-content > div');

    sectionLinks.forEach(link => {
        link.addEventListener('click', () => {
            const targetSection = document.getElementById(link.dataset.section);

            sections.forEach(section => section.classList.add('hidden'));
            targetSection.classList.remove('hidden');
        });
    });

    // Update Leave Status using AJAX
    window.updateLeaveStatus = function (employeeCode, status) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "update_leave_status.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert("Leave status updated to " + status);
                location.reload(); // Reload the page to reflect changes
            }
        };
        xhr.send("employee_code=" + employeeCode + "&status=" + status);
    }
});
