<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .prescription-container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #0056b3;
            color: #fff;
            padding: 15px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }

        .content {
            padding: 20px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section h3 {
            border-bottom: 2px solid #0056b3;
            padding-bottom: 5px;
            color: #333;
            font-size: 18px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
        }

        .info-row p {
            margin: 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .footer {
            margin-top: 460px;
            text-align: center;
            font-size: 14px;
            color: #777;
        }

        .print-btn {
            text-align: center;
            margin-top: 20px;
        }

        .print-btn button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #0056b3;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .print-btn button:hover {
            background-color: #004494;
        }

        @media print {
            .print-btn {
                display: none;
            }

            .prescription-container {
                border: none;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="prescription-container" id="prescription-details">
        <div class="header">
            <h1>Health Choice Clinic</h1>
            <p>123 Main Street, City, State - 12345 | Phone: (123) 456-7890</p>
        </div>

        <div class="content">
            <!-- Patient Information -->
            <div class="section" id="patient-info">
                <h3>Patient Information</h3>
            </div>

            <!-- Appointment Information -->
            <div class="section" id="appointment-info">
                <h3>Appointment Details</h3>
            </div>

            <!-- Medications & Instructions -->
            <div class="section" id="medications-info">
                <h3>Medications & Instructions</h3>
                <table class="table" id="medication-table">
                    <thead>
                        <tr>
                            <th>Medication</th>
                            <th>Dosage</th>
                            <th>Instructions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>Doctor's Signature: ____________________</p>
                <p>Contact us for any assistance: (123) 456-7890</p>
            </div>
        </div>
    </div>

    <div class="print-btn">
        <button onclick="window.print()">Print Prescription</button>
    </div>

    <script>
        // Fetch and render the prescription data from API
        function fetchPrescription() {
            fetch('http://127.0.0.1:8000/api/prescriptions')
                .then(response => response.json())
                .then(data => {
                    if (data.prescriptions && data.prescriptions.length > 0) {
                        const prescription = data.prescriptions[0]; // Assuming only one prescription for simplicity
                        const prescriptionDetails = JSON.parse(prescription.prescription_details);
                        
                        // Patient Info
                        const patientInfo = document.getElementById('patient-info');
                        patientInfo.innerHTML = `
                            <div class="info-row">
                                <p><strong>Patient ID:</strong> ${prescriptionDetails['Patient ID']}</p>
                                <p><strong>Patient Name:</strong> ${prescriptionDetails['Patient Name']}</p>
                            </div>
                            <div class="info-row">
                                <p><strong>Phone:</strong> ${prescriptionDetails['Patient Phone']}</p>
                                <p><strong>Address:</strong> ${prescriptionDetails['Patient Address']}</p>
                            </div>
                            <div class="info-row">
                                <p><strong>Visit Count:</strong> ${prescriptionDetails['Visit Count']}</p>
                            </div>
                        `;
                        
                        // Appointment Info
                        const appointmentInfo = document.getElementById('appointment-info');
                        appointmentInfo.innerHTML = `
                            <div class="info-row">
                                <p><strong>Appointment ID:</strong> ${prescriptionDetails['Appointment ID']}</p>
                                <p><strong>Date:</strong> ${prescriptionDetails['Appointment Date']}</p>
                            </div>
                            <div class="info-row">
                                <p><strong>Doctor:</strong> ${prescriptionDetails['Doctor Name']}</p>
                            </div>
                        `;

                        // Medications Table
                        const medicationTable = document.getElementById('medication-table').getElementsByTagName('tbody')[0];
                        const medications = prescriptionDetails['Medications'] || []; // Assuming Medications is an array
                        medications.forEach(med => {
                            const row = medicationTable.insertRow();
                            row.innerHTML = `
                                <td>${med.name}</td>
                                <td>${med.dosage}</td>
                                <td>${med.instructions}</td>
                            `;
                        });
                    } else {
                        document.getElementById('prescription-details').innerHTML = '<p>No prescriptions found.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching prescription:', error);
                    document.getElementById('prescription-details').innerHTML = '<p>Error fetching prescription.</p>';
                });
        }

        // Call the function to fetch the prescription data
        fetchPrescription();
    </script>
</body>
</html>
