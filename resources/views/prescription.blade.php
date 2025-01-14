<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        h1, h2 {
            text-align: center;
        }
        .form-container, .list-container {
            background: #fff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            display: block;
            width: 100%;
            background: #5cb85c;
            color: white;
            border: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background: #4cae4c;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background: #333;
            color: white;
        }
        td button {
            padding: 5px 10px;
            background: #d9534f;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        td button:hover {
            background: #c9302c;
        }
        .no-data {
            text-align: center;
            font-size: 18px;
            color: #888;
        }
    </style>
</head>
<body>
    <h1>Prescription Management</h1>

    <!-- Prescription Form -->
    <div class="form-container">
        <h2>Add Prescription</h2>
        <form id="prescriptionForm">
            <label for="appointmentId">Appointment ID:</label>
            <input type="text" id="appointmentId" required />

            <label for="patientName">Patient Name:</label>
            <input type="text" id="patientName" readonly />

            <label for="medications">Medications:</label>
            <textarea id="medications" required></textarea>

            <label for="instructions">Instructions:</label>
            <textarea id="instructions" required></textarea>

            <button type="submit">Add Prescription</button>
        </form>
    </div>

    <!-- Prescription List -->
    <div class="list-container">
        <h2>Previous Prescriptions</h2>
        <table id="prescriptionTable">
            <thead>
                <tr>
                    <th>Prescription ID</th>
                    <th>Appointment ID</th>
                    <th>Patient Name</th>
                    <th>Medications</th>
                    <th>Instructions</th>
                </tr>
            </thead>
            <tbody id="prescriptionBody">
                <!-- Data will be dynamically inserted here -->
            </tbody>
        </table>
        <div id="noPrescriptions" class="no-data" style="display: none;">No Previous Prescriptions Found</div>
    </div>

    <script>
        // Fetch and display previous prescriptions when appointment ID is entered
        document.getElementById('appointmentId').addEventListener('input', async function () {
            const appointmentId = this.value;

            if (appointmentId.trim() === '') {
                document.getElementById('patientName').value = '';
                updatePrescriptionTable([]);
                return;
            }

            try {
                // Fetch appointment details
                const appointmentResponse = await fetch(`http://127.0.0.1:8000/api/appointments/${id}`);
                if (!appointmentResponse.ok) {
                    throw new Error('Appointment not found');
                }
                const appointment = await appointmentResponse.json();
                document.getElementById('name').value = appointment.patient.name;

                // Fetch prescriptions for the same patient
                const prescriptionsResponse = await fetch(`http://127.0.0.1:8000/api/prescriptions?patient_id=${appointment.patient.id}`);
                const prescriptions = await prescriptionsResponse.json();
                updatePrescriptionTable(prescriptions);
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('patientName').value = '';
                updatePrescriptionTable([]);
            }
        });

        // Update the prescriptions table
        function updatePrescriptionTable(prescriptions) {
            const tableBody = document.getElementById('prescriptionBody');
            const noDataDiv = document.getElementById('noPrescriptions');

            tableBody.innerHTML = '';

            if (prescriptions.length === 0) {
                noDataDiv.style.display = 'block';
                return;
            }

            noDataDiv.style.display = 'none';

            prescriptions.forEach((prescription) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${prescription.id}</td>
                    <td>${prescription.appointment_id}</td>
                    <td>${prescription.patient_name}</td>
                    <td>${prescription.medications}</td>
                    <td>${prescription.instructions}</td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Handle form submission
        document.getElementById('prescriptionForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const appointmentId = document.getElementById('appointmentId').value;
            const medications = document.getElementById('medications').value;
            const instructions = document.getElementById('instructions').value;

            try {
                const response = await fetch('http://127.0.0.1:8000/api/prescriptions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        appointment_id: appointmentId,
                        medications,
                        instructions,
                    }),
                });

                if (!response.ok) {
                    throw new Error('Failed to add prescription');
                }

                alert('Prescription added successfully');
                document.getElementById('prescriptionForm').reset();
                updatePrescriptionTable([]);
            } catch (error) {
                console.error('Error:', error);
                alert('Error adding prescription');
            }
        });
    </script>
</body>
</html>
