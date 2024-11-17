document.addEventListener("DOMContentLoaded", function () {
    const leaveForm = document.getElementById("leave-form");
    const leaveHistoryTable = document.querySelector("#leave-history-table tbody");
    const salarySlipsLink = document.querySelector("#salary-slips-link");
    const employeeSectionLink = document.querySelector("#employee-section-link");
    const contentSections = document.querySelectorAll(".main-content > div");

    // Utility function to switch between content sections
    function switchSection(sectionId) {
        contentSections.forEach((section) => {
            section.style.display = section.id === sectionId ? "block" : "none";
        });
    }

    // Event listener for the leave form submission
    if (leaveForm) {
        leaveForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(leaveForm);

            // Send the form data via AJAX
            fetch("leaves.php", {
                method: "POST",
                body: formData,
            })
            .then((response) => {
                if (!response.ok) throw new Error("Network response was not ok");
                return response.text();
            })
            .then((data) => {
                // If the form submission is successful, refresh the leave history
                loadLeaveHistory();
                leaveForm.reset();
            })
            .catch((error) => console.error("Error submitting leave form:", error));
        });

        // Load leave history via AJAX
        function loadLeaveHistory() {
            fetch("leaves.php", {
                headers: { "X-Requested-With": "XMLHttpRequest" },
            })
            .then((response) => {
                if (!response.ok) throw new Error("Network response was not ok");
                return response.text();
            })
            .then((data) => {
                leaveHistoryTable.innerHTML = data;
            })
            .catch((error) => console.error("Error loading leave history:", error));
        }

        // Initial load of leave history if needed
        loadLeaveHistory();
    }

    // Handle the Salary Slips section for the admin
    if (salarySlipsLink) {
        salarySlipsLink.addEventListener("click", function (e) {
            e.preventDefault(); // Prevent default link behavior
            switchSection("salary-slips"); // Show the Salary Slips section
        });
    }

    // Handle Employee Section navigation
    if (employeeSectionLink) {
        employeeSectionLink.addEventListener("click", function (e) {
            e.preventDefault(); // Prevent default link behavior
            switchSection("employee-section"); // Show the Employee Section
        });
    }

    // Default Section to display (Employee Section)
    switchSection("employee-section");

    // Handling leave approval/rejection actions
    const leaveActions = document.querySelectorAll('.actions .material-icons');

    leaveActions.forEach(action => {
        action.addEventListener('click', function (e) {
            e.preventDefault();

            const leaveId = this.closest('tr').dataset.id; // Get leave ID from the row
            const actionType = this.dataset.action; // 'approve' or 'reject'

            // Confirm action
            const confirmAction = confirm(
                `Are you sure you want to ${actionType === 'approve' ? 'approve' : 'reject'} this leave request?`
            );

            if (!confirmAction) return;

            // Send an AJAX request to process the action
            fetch('leave_management.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    id: leaveId,
                    action: actionType,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const row = this.closest('tr');
                    const statusCell = row.querySelector('td:nth-child(7)'); // Status column
                    const actionsCell = row.querySelector('td:nth-child(8)'); // Actions column

                    if (statusCell && actionsCell) {
                        statusCell.textContent = data.new_status;
                        actionsCell.innerHTML = '<span>N/A</span>'; // Disable further actions
                    }

                    alert(`Leave request ${actionType === 'approve' ? 'approved' : 'rejected'} successfully!`);
                } else {
                    alert(data.message || 'An error occurred. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing the request. Please try again.');
            });
        });
    });
});
