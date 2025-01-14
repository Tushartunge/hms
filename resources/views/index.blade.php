<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .no-data {
            text-align: center;
            font-size: 18px;
            color: #888;
        }
    </style>
</head>
<body>
    <h1>Appointments Details</h1>
    <table id="appointmentsTable">
        <thead>
            <tr>
                <th>Appointment ID</th>
                <th>Patient Name</th>
                <th>Patient Email</th>
                <th>Patient Phone</th>
                <th>Doctor Name</th>
                <th>Doctor Specialization</th>
                <th>Appointment Date</th>
                <th>Type</th>
                <th>Mode</th>
                <th>Status</th>
                <th>visit count</th>
            </tr>
        </thead>
        <tbody id="appointmentsBody">
            <!-- Appointment rows will be added dynamically -->
        </tbody>
    </table>
    <div id="noData" class="no-data" style="display: none;">No Appointments Found</div>

    <script>
        // Fetch and display appointment data
        async function fetchAppointments() {
            try {
                const response = await fetch('http://127.0.0.1:8000/api/appointments'); // Replace with your API URL
                const data = await response.json();

                const appointmentsBody = document.getElementById('appointmentsBody');
                const noDataDiv = document.getElementById('noData');

                // Clear the table body before adding data
                appointmentsBody.innerHTML = '';

                if (data.appointments.length === 0) {
                    noDataDiv.style.display = 'block'; // Show "No Appointments" message
                    return;
                }

                noDataDiv.style.display = 'none'; // Hide "No Appointments" message

                // Loop through appointments and create table rows
                data.appointments.forEach(appointment => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td>${appointment.id}</td>
                        <td>${appointment.patient.name}</td>
                        <td>${appointment.patient.email}</td>
                        <td>${appointment.patient.phone}</td>
                        <td>${appointment.doctor.name}</td>
                        <td>${appointment.doctor.specialization}</td>
                        <td>${appointment.appointment_date}</td>
                        <td>${appointment.type}</td>
                        <td>${appointment.mode}</td>
                        <td>${appointment.status}</td>
                        <td>${appointment.patient.visit_count}</td>
                    `;

                    appointmentsBody.appendChild(row);
                });
            } catch (error) {
                console.error('Error fetching appointments:', error);
            }
        }

        // Fetch appointments on page load
        document.addEventListener('DOMContentLoaded', fetchAppointments);
    </script>
    <a href="/pre">next</a>
</body>
</html>
