<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Health Records</h1>

    <!-- Display Patient Name -->
    <h2>Patient: <span id="patientName"></span></h2>
    
    <h2>List Records</h2>
    <button onclick="fetchRecords()">Fetch Records</button>
    <table id="recordsTable">
        <thead>
            <tr>
                <!-- <th>Patient Name</th> -->
                <th>ID</th>
                <th>Description</th>
                <th>Date</th>
                <th>Medication</th>
                <th>Dosage</th>
                <th>Frequency</th>
                <th>Attachment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <h2>Create Record</h2>
    <form id="createForm">
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" id="date" name="date" required>
        </div>
        <div class="form-group">
            <label for="medication">Medication</label>
            <input type="text" id="medication" name="medication" required>
        </div>
        <div class="form-group">
            <label for="dosage">Dosage</label>
            <input type="text" id="dosage" name="dosage" required>
        </div>
        <div class="form-group">
            <label for="frequency">Frequency</label>
            <input type="text" id="frequency" name="frequency" required>
        </div>
        <div class="form-group">
            <label for="attachment">Attachment</label>
            <input type="file" id="attachment" name="attachment">
        </div>
        <button type="submit">Create Record</button>
    </form>

    <script>
        const apiBase = 'http://127.0.0.1:8000/api'; // Replace with your actual API base URL
        const patientId = 2; // Replace with actual patient ID

        // Fetch and display the patient's name
        async function fetchPatientDetails() {
            const response = await fetch(`${apiBase}/patients/${patientId}`);
            const data = await response.json();
            document.getElementById('patientName').innerText = data.name;
        }

        async function fetchRecords() {
            const response = await fetch(`${apiBase}/patients/${patientId}/health-records`);
            const data = await response.json();

            const tableBody = document.querySelector('#recordsTable tbody');
            tableBody.innerHTML = '';

            data.records.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                  <!--  <td>${data.name}</td>  Display patient name in the table -->
                    <td>${record.id}</td>
                    <td>${record.description}</td>
                    <td>${record.date}</td>
                    <td>${record.medication}</td>
                    <td>${record.dosage}</td>
                    <td>${record.frequency}</td>
                    <td>${record.attachment_path ? `<a href="/storage/${record.attachment_path}" target="_blank">View</a>` : 'N/A'}</td>
                    <td>
                        <button onclick="deleteRecord(${record.id})">Delete</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        async function deleteRecord(recordId) {
            if (confirm('Are you sure you want to delete this record?')) {
                const response = await fetch(`${apiBase}/health-records/${recordId}`, {
                    method: 'DELETE',
                });
                const data = await response.json();
                alert(data.message);
                fetchRecords();
            }
        }

        document.getElementById('createForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);

            const response = await fetch(`${apiBase}/patients/${patientId}/health-records`, {
                method: 'POST',
                body: formData,
            });
            
            const data = await response.json();

            if (data.message) {
                alert(data.message);
                e.target.reset();
                fetchRecords();
            } else {
                alert('Error: ' + JSON.stringify(data));
            }
        });

        // Call the function to fetch patient details when the page loads
        fetchPatientDetails();
    </script>
</body>
</html>
