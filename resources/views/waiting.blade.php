<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waiting Room Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 60%;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        .waiting-count {
            text-align: center;
            margin-bottom: 20px;
        }

        .waiting-room-list ul {
            list-style-type: none;
            padding: 0;
        }

        .waiting-room-list li {
            padding: 10px;
            margin: 5px;
            background-color: #f1f1f1;
            border-radius: 5px;
            cursor: pointer;
        }

        .waiting-room-list li:hover {
            background-color: #e0e0e0;
        }

        .patient-details {
            display: none;
            margin-top: 20px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .message {
            margin-top: 20px;
            text-align: center;
            padding: 10px;
            font-size: 18px;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Waiting Room Management</h1>

    <!-- Waiting Count -->
    <div class="waiting-count" id="waiting-count">
        <h2>Patients in Waiting: <span id="waiting-count-value">0</span></h2>
    </div>

    <!-- Waiting Room List -->
    <div class="waiting-room-list">
        <h2>Waiting Room List</h2>
        <ul id="waiting-room-list">
            <!-- Entries added dynamically -->
        </ul>
    </div>

    <!-- Patient Details -->
    <div class="patient-details" id="patient-details">
        <h2>Patient Details</h2>
        <p>Patient Name: <span id="patient-name-value"></span></p>
        <p>Appointment ID: <span id="appointment-id-value"></span></p>
        <p>Status: <span id="status-value">Waiting</span></p>
        <button id="start-consultation-btn" disabled>Start Consultation</button> <!-- Disabled button -->
        <button id="complete-consultation-btn" onclick="changeStatus('completed')" disabled>Complete Consultation</button>
    </div>

    <!-- Success/Error Message -->
    <div id="message" class="message"></div>
</div>

<script>
   const BASE_URL = 'http://127.0.0.1:8000/api'; // Base API URL

// Fetch waiting room data and populate the list
async function loadWaitingRoomList() {
    try {
        const response = await fetch(`${BASE_URL}/waitingrooms`);
        if (!response.ok) throw new Error('Failed to fetch waiting room data.');

        const waitingRooms = await response.json();

        const waitingRoomList = document.getElementById('waiting-room-list');
        waitingRoomList.innerHTML = ''; // Clear existing list

        // Update the waiting count
        const waitingCount = waitingRooms.filter(room => room.status === 'waiting').length;
        document.getElementById('waiting-count-value').textContent = waitingCount;

        // Populate the list
        waitingRooms.forEach(room => {
            const listItem = document.createElement('li');
            listItem.textContent = `Room ID: ${room.id} | Patient: ${room.patient.name} | Appointment: ${room.appointment.id}`;
            listItem.onclick = () => showPatientDetails(room);
            waitingRoomList.appendChild(listItem);
        });
    } catch (error) {
        console.error('Error loading waiting room list:', error);
        showMessage('Error loading waiting room data. Please try again.', 'error');
    }
}

// Show patient details
function showPatientDetails(room) {
    const patientDetails = document.getElementById('patient-details');
    patientDetails.style.display = 'block';

    document.getElementById('patient-name-value').textContent = room.patient.name || 'N/A';
    document.getElementById('appointment-id-value').textContent = room.appointment.id || 'N/A';
    document.getElementById('status-value').textContent = capitalizeFirstLetter(room.status);

    const completeButton = document.getElementById('complete-consultation-btn');

    // Enable "Complete Consultation" only if the status is "waiting" or "in_consultation"
    completeButton.disabled = !(room.status === 'waiting' || room.status === 'in_consultation');

    // Store selected room in a scoped variable
    window.selectedRoom = room;
}

// Change status of a patient (only for completing consultation)
async function changeStatus(newStatus) {
    if (newStatus !== 'completed') {
        console.warn('Only "completed" status updates are allowed.');
        return;
    }

    if (!window.selectedRoom) {
        console.error('No room selected. Cannot update status.');
        showMessage('Please select a patient before completing the consultation.', 'error');
        return;
    }

    try {
        const response = await fetch(`${BASE_URL}/waiting-rooms/${window.selectedRoom.id}/complete`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: newStatus }),
        });

        if (!response.ok) throw new Error('Failed to update status.');

        const updatedRoom = await response.json();

        showMessage(`${updatedRoom.patient.name}'s status updated to "${newStatus}".`, 'success');

        // Reload the waiting room list
        loadWaitingRoomList();

        // Clear patient details and hide the section
        document.getElementById('patient-details').style.display = 'none';
    } catch (error) {
        console.error('Error updating status:', error);
        showMessage('Failed to update status. Please try again.', 'error');
    }
}

// Utility: Capitalize the first letter of a string
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

// Show success/error messages
function showMessage(message, type) {
    const messageBox = document.getElementById('message');
    messageBox.textContent = message;
    messageBox.className = `message ${type}`;
}

// Load data on page load
window.onload = loadWaitingRoomList;

</script>

</body>
</html>
