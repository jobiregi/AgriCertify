<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "agri_certify";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user email from session
$user_email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Application Tracking - Nyeri County</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            nyeriGreen: '#006400',
            nyeriLightGreen: '#4caf50',
            nyeriBrown: '#4e342e',
            nyeriBeige: '#fdfaf6',
            nyeriYellow: '#ffeb3b'
          }
        }
      }
    }
  </script>
  <style>
    .status-badge {
      padding: 0.35rem 0.65rem;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 600;
    }
    .application-card {
      transition: all 0.3s ease;
      border-left: 4px solid #006400;
    }
    .application-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .progress-bar {
      height: 8px;
      border-radius: 4px;
      background-color: #e5e7eb;
      overflow: hidden;
    }
    .progress-fill {
      height: 100%;
      border-radius: 4px;
      background-color: #006400;
      transition: width 0.5s ease-in-out;
    }
    .loading-spinner {
      display: inline-block;
      width: 20px;
      height: 20px;
      border: 3px solid rgba(0, 100, 0, 0.3);
      border-radius: 50%;
      border-top-color: #006400;
      animation: spin 1s ease-in-out infinite;
    }
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
  </style>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col">
  <!-- Navbar -->
  <nav class="bg-nyeriGreen text-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
      <div class="flex items-center">
        <div class="h-12 w-12 rounded-full bg-white flex items-center justify-center mr-3">
          <span class="text-nyeriGreen font-bold text-lg">NC</span>
        </div>
        <div>
          <h1 class="text-xl font-bold">County Government Of Nyeri</h1>
          <p class="text-sm text-gray-200">Applicant Portal</p>
        </div>
      </div>
      <div class="flex items-center space-x-4">
        <span class="text-sm">Welcome, <?php echo $_SESSION['email']; ?></span>
        <a href="logout.php" class="hover:text-red-300 text-sm" onclick="return confirmLogout()">
          <i class="fas fa-sign-out-alt mr-1"></i>Logout
        </a>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="flex-grow max-w-7xl mx-auto py-8 px-4 w-full">
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h2 class="text-3xl font-bold text-nyeriGreen mb-4 md:mb-0">Application Tracking</h2>
        <div class="flex space-x-2">
          <button id="refreshBtn" class="bg-nyeriGreen text-white py-2 px-4 rounded-lg hover:bg-green-800 transition-colors text-sm">
            <i class="fas fa-sync-alt mr-2"></i>Refresh
          </button>
        </div>
      </div>
      
      <p class="mb-6 text-gray-600">Track the status of your submitted applications. You can view details, check progress, and take necessary actions.</p>
      
      <!-- Filter Section -->
      <div class="bg-nyeriBeige p-4 rounded-lg mb-6">
        <div class="flex flex-col md:flex-row gap-4 items-end">
          <div class="w-full md:w-auto">
            <label class="block text-sm font-medium text-gray-700 mb-1">Search Applications</label>
            <input type="text" id="searchInput" placeholder="Search by application ID or type..." class="w-full p-3 border border-gray-300 rounded-lg">
          </div>
          <div class="w-full md:w-auto">
            <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Type</label>
            <select id="typeFilter" class="w-full p-3 border border-gray-300 rounded-lg">
              <option value="all">All Types</option>
              <option value="coffee_nursery">Coffee Nursery</option>
              <option value="commercial_milling">Commercial Milling</option>
              <option value="warehouse">Warehouse</option>
              <option value="coffee_roaster">Coffee Roaster</option>
              <option value="grower_notification">Grower Notification</option>
              <option value="pulping_station">Pulping Station</option>
              <option value="grow_miller">Grow Miller</option>
            </select>
          </div>
          <div class="w-full md:w-auto">
            <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Status</label>
            <select id="statusFilter" class="w-full p-3 border border-gray-300 rounded-lg">
              <option value="all">All Statuses</option>
              <option value="submitted">Submitted</option>
              <option value="under_review">Under Review</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
              <option value="requires_action">Requires Action</option>
            </select>
          </div>
          <button id="applyFilters" class="bg-nyeriGreen text-white p-3 rounded-lg hover:bg-green-800 transition-colors w-full md:w-auto">
            Apply Filters
          </button>
        </div>
      </div>
      
      <!-- Loading Indicator -->
      <div id="loadingIndicator" class="text-center py-8">
        <div class="loading-spinner mx-auto mb-4"></div>
        <p>Loading your applications...</p>
      </div>
      
      <!-- Applications List -->
      <div id="applicationsContainer" class="space-y-4 hidden">
        <!-- Application cards will be dynamically inserted here -->
      </div>
      
      <!-- No applications message -->
      <div id="noApplications" class="hidden text-center py-12">
        <div class="mb-6 text-6xl text-gray-300">
          <i class="fas fa-file-alt"></i>
        </div>
        <h3 class="text-2xl font-semibold text-gray-500 mb-4">No Applications Found</h3>
        <p class="text-gray-500 mb-6">You haven't submitted any applications yet.</p>
      </div>
    </div>
  </main>

  <!-- Application Detail Modal -->
  <div id="applicationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
      <div class="p-6 border-b">
        <div class="flex justify-between items-center">
          <h3 class="text-2xl font-bold text-nyeriGreen">Application Details</h3>
          <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>
      </div>
      <div class="p-6">
        <div class="mb-6">
          <h4 class="text-lg font-semibold mb-2">Application Progress</h4>
          <div class="progress-bar mb-2">
            <div id="progressFill" class="progress-fill" style="width: 60%"></div>
          </div>
          <p class="text-sm text-gray-600">Your application is <span id="progressText">under review</span></p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <div>
            <h4 class="text-lg font-semibold mb-2">Application Information</h4>
            <div class="space-y-2">
              <p><span class="font-medium">Application ID:</span> <span id="modalAppId">APP-2023-001</span></p>
              <p><span class="font-medium">Type:</span> <span id="modalAppType">Coffee Nursery Certificate</span></p>
              <p><span class="font-medium">Submitted On:</span> <span id="modalAppDate">October 15, 2023</span></p>
              <p><span class="font-medium">Last Updated:</span> <span id="modalAppUpdated">November 5, 2023</span></p>
            </div>
          </div>
          
          <div>
            <h4 class="text-lg font-semibold mb-2">Status Information</h4>
            <div class="space-y-2">
              <p><span class="font-medium">Current Status:</span> <span id="modalAppStatus" class="status-badge bg-yellow-100 text-yellow-800">Under Review</span></p>
              <p><span class="font-medium">Assigned Officer:</span> <span id="modalAppOfficer">Jane Mwangi</span></p>
              <p><span class="font-medium">Department:</span> <span id="modalAppDept">Agriculture & Coffee Board</span></p>
              <p><span class="font-medium">Estimated Completion:</span> <span id="modalAppEstimate">December 1, 2023</span></p>
            </div>
          </div>
        </div>
        
        <div class="mb-6">
          <h4 class="text-lg font-semibold mb-2">Application Documents</h4>
          <div id="documentList" class="mb-4">
            <!-- Documents will be listed here -->
          </div>
          
          <!-- File Upload Section -->
          <div class="border border-dashed border-gray-300 rounded-lg p-4 mt-4">
            <h5 class="font-medium mb-2">Upload Additional Documents</h5>
            <form id="uploadForm" enctype="multipart/form-data">
              <input type="hidden" id="uploadAppId" name="appId" value="">
              <input type="hidden" id="uploadAppType" name="appType" value="">
              <div class="flex items-center">
                <input type="file" name="document" id="documentInput" class="flex-grow p-2 border border-gray-300 rounded-l-lg" required>
                <button type="submit" class="bg-nyeriGreen text-white py-2 px-4 rounded-r-lg hover:bg-green-800 transition-colors">
                  <i class="fas fa-upload"></i>
                </button>
              </div>
              <p class="text-xs text-gray-500 mt-2">Max file size: 5MB. Supported formats: PDF, JPG, PNG</p>
            </form>
            <div id="uploadStatus" class="mt-2 hidden"></div>
          </div>
        </div>
        
        <div class="mb-6">
          <h4 class="text-lg font-semibold mb-2">Next Steps</h4>
          <ul class="list-disc pl-5 space-y-1 text-gray-700" id="nextStepsList">
            <!-- Next steps will be listed here -->
          </ul>
        </div>
        
        <div class="mb-6" id="actionRequiredSection">
          <!-- Action required section will be shown if needed -->
        </div>
        
        <div class="flex justify-end space-x-3 pt-4 border-t">
          <button onclick="closeModal()" class="bg-gray-200 text-gray-800 py-2 px-6 rounded-lg hover:bg-gray-300 transition-colors">
            Close
          </button>
          <button id="updateApplicationBtn" class="bg-nyeriGreen text-white py-2 px-6 rounded-lg hover:bg-green-800 transition-colors">
            <i class="fas fa-edit mr-2"></i>Update Application
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Update Application Modal -->
  <div id="updateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
      <div class="p-6 border-b">
        <div class="flex justify-between items-center">
          <h3 class="text-2xl font-bold text-nyeriGreen">Update Application</h3>
          <button onclick="closeUpdateModal()" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>
      </div>
      <div class="p-6">
        <form id="updateForm">
          <input type="hidden" id="updateAppId" name="appId" value="">
          <input type="hidden" id="updateAppType" name="appType" value="">
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="updateMessage">
              Update Message
            </label>
            <textarea id="updateMessage" name="message" rows="4" class="w-full p-3 border border-gray-300 rounded-lg" placeholder="Describe the updates you've made to your application..." required></textarea>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
              Upload Updated Documents
            </label>
            <input type="file" name="updatedDocuments[]" multiple class="w-full p-2 border border-gray-300 rounded-lg">
            <p class="text-xs text-gray-500 mt-2">You can select multiple files. Max file size: 5MB each.</p>
          </div>
          <div class="flex justify-end space-x-3 pt-4">
            <button type="button" onclick="closeUpdateModal()" class="bg-gray-200 text-gray-800 py-2 px-6 rounded-lg hover:bg-gray-300 transition-colors">
              Cancel
            </button>
            <button type="submit" class="bg-nyeriGreen text-white py-2 px-6 rounded-lg hover:bg-green-800 transition-colors">
              Submit Update
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-gray-800 text-white text-center py-6 mt-auto">
    <div class="max-w-7xl mx-auto px-4">
      <p>&copy; 2025 County Government of Nyeri – All Rights Reserved</p>
      <p class="text-sm text-gray-400 mt-2">Agriculture & Coffee Board Department</p>
    </div>
  </footer>

  <script>
    // Global variables
    let applications = [];
    let currentAppId = null;
    let currentAppType = null;

    // Function to fetch applications from the server
    async function fetchApplications() {
      showLoading();
      
      try {
        const response = await fetch('api/get_applications.php');
        const data = await response.json();
        
        if (data.success) {
          applications = data.applications;
          renderApplications();
        } else {
          showError('Failed to load applications: ' + data.message);
        }
      } catch (error) {
        showError('Network error: ' + error.message);
      }
    }
    
    // Function to render application cards
    function renderApplications() {
      const container = document.getElementById('applicationsContainer');
      const noApplications = document.getElementById('noApplications');
      const searchTerm = document.getElementById('searchInput').value.toLowerCase();
      const typeFilter = document.getElementById('typeFilter').value;
      const statusFilter = document.getElementById('statusFilter').value;
      
      // Clear existing content
      container.innerHTML = '';
      
      // Filter applications
      const filteredApplications = applications.filter(app => {
        const matchesSearch = app.id.toLowerCase().includes(searchTerm) || 
                             app.type.toLowerCase().includes(searchTerm);
        const matchesType = typeFilter === 'all' || app.app_type === typeFilter;
        const matchesStatus = statusFilter === 'all' || app.status === statusFilter;
        return matchesSearch && matchesType && matchesStatus;
      });
      
      if (filteredApplications.length === 0) {
        container.classList.add('hidden');
        noApplications.classList.remove('hidden');
        hideLoading();
        return;
      }
      
      noApplications.classList.add('hidden');
      
      filteredApplications.forEach(app => {
        const card = document.createElement('div');
        card.className = 'application-card bg-white rounded-lg shadow p-6';
        
        card.innerHTML = `
          <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
            <div>
              <h3 class="text-xl font-semibold text-nyeriGreen">${app.type}</h3>
              <p class="text-gray-600">Application ID: ${app.id}</p>
            </div>
            <span class="status-badge ${getStatusClass(app.status)} mt-2 md:mt-0">${app.status.replace('_', ' ')}</span>
          </div>
          
          <div class="mb-4">
            <div class="progress-bar mb-2">
              <div class="progress-fill" style="width: ${app.progress}%"></div>
            </div>
            <p class="text-sm text-gray-600">Submitted on ${formatDate(app.submitted)} • Last updated on ${formatDate(app.updated)}</p>
          </div>
          
          <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
              <p class="text-gray-700"><i class="fas fa-user-tie mr-2 text-nyeriGreen"></i> Assigned Officer: ${app.officer || 'Not assigned'}</p>
              <p class="text-gray-700"><i class="fas fa-building mr-2 text-nyeriGreen"></i> Department: ${app.department || 'Agriculture Department'}</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-2">
              <button class="view-details bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors text-sm" data-id="${app.id}" data-type="${app.app_type}">
                <i class="fas fa-eye mr-2"></i>View Details
              </button>
              ${app.requires_action ? `
                <button class="update-app bg-nyeriLightGreen text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors text-sm" data-id="${app.id}" data-type="${app.app_type}">
                  <i class="fas fa-edit mr-2"></i>Update
                </button>
              ` : ''}
            </div>
          </div>
          
          ${app.requires_action ? `
            <div class="mt-4 bg-red-50 border-l-4 border-red-400 p-4">
              <div class="flex">
                <div class="flex-shrink-0">
                  <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                  <p class="text-sm text-red-700">
                    ${app.action_message || 'Action required on your application'}
                  </p>
                </div>
              </div>
            </div>
          ` : ''}
        `;
        
        container.appendChild(card);
      });
      
      // Add event listeners to view details buttons
      document.querySelectorAll('.view-details').forEach(button => {
        button.addEventListener('click', function() {
          const appId = this.getAttribute('data-id');
          const appType = this.getAttribute('data-type');
          viewApplicationDetails(appId, appType);
        });
      });
      
      // Add event listeners to update buttons
      document.querySelectorAll('.update-app').forEach(button => {
        button.addEventListener('click', function() {
          const appId = this.getAttribute('data-id');
          const appType = this.getAttribute('data-type');
          openUpdateModal(appId, appType);
        });
      });
      
      container.classList.remove('hidden');
      hideLoading();
    }
    
    // Function to get CSS class for status badge
    function getStatusClass(status) {
      switch(status) {
        case 'submitted': return 'bg-blue-100 text-blue-800';
        case 'under_review': return 'bg-yellow-100 text-yellow-800';
        case 'approved': return 'bg-green-100 text-green-800';
        case 'rejected': return 'bg-red-100 text-red-800';
        case 'requires_action': return 'bg-orange-100 text-orange-800';
        default: return 'bg-gray-100 text-gray-800';
      }
    }
    
    // Format date for display
    function formatDate(dateString) {
      if (!dateString) return 'Not available';
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return new Date(dateString).toLocaleDateString(undefined, options);
    }
    
    // Function to view application details
    async function viewApplicationDetails(appId, appType) {
      try {
        const response = await fetch(`api/get_application_details.php?appId=${appId}&appType=${appType}`);
        const data = await response.json();
        
        if (data.success) {
          const app = data.application;
          currentAppId = appId;
          currentAppType = appType;
          
          // Populate modal with application details
          document.getElementById('progressFill').style.width = `${app.progress || 60}%`;
          document.getElementById('progressText').textContent = app.status ? app.status.replace('_', ' ') : 'under review';
          document.getElementById('modalAppId').textContent = app.id;
          document.getElementById('modalAppType').textContent = app.type;
          document.getElementById('modalAppDate').textContent = formatDate(app.submitted);
          document.getElementById('modalAppUpdated').textContent = formatDate(app.updated);
          document.getElementById('modalAppStatus').textContent = app.status ? app.status.replace('_', ' ') : 'Under Review';
          document.getElementById('modalAppStatus').className = `status-badge ${getStatusClass(app.status || 'under_review')}`;
          document.getElementById('modalAppOfficer').textContent = app.officer || 'Not assigned yet';
          document.getElementById('modalAppDept').textContent = app.department || 'Agriculture Department';
          document.getElementById('modalAppEstimate').textContent = formatDate(app.estimated_completion);
          document.getElementById('uploadAppId').value = appId;
          document.getElementById('uploadAppType').value = appType;
          
          // Update progress steps
          const stepsList = document.getElementById('nextStepsList');
          stepsList.innerHTML = '';
          
          if (app.next_steps && app.next_steps.length > 0) {
            app.next_steps.forEach(step => {
              const li = document.createElement('li');
              li.innerHTML = step.completed ? 
                `<span class="text-green-600"><i class="fas fa-check-circle mr-2"></i>${step.description}</span>` :
                `<span class="text-gray-500">${step.description}</span>`;
              stepsList.appendChild(li);
            });
          } else {
            // Default steps if none provided
            const defaultSteps = [
              {description: 'Application submitted', completed: true},
              {description: 'Initial review', completed: app.status !== 'submitted'},
              {description: 'Field inspection', completed: app.status === 'approved' || app.status === 'rejected'},
              {description: 'Final approval', completed: app.status === 'approved'}
            ];
            
            defaultSteps.forEach(step => {
              const li = document.createElement('li');
              li.innerHTML = step.completed ? 
                `<span class="text-green-600"><i class="fas fa-check-circle mr-2"></i>${step.description}</span>` :
                `<span class="text-gray-500">${step.description}</span>`;
              stepsList.appendChild(li);
            });
          }
          
          // Show/hide action required section
          const actionSection = document.getElementById('actionRequiredSection');
          if (app.requires_action) {
            actionSection.innerHTML = `
              <h4 class="text-lg font-semibold mb-2">Action Required</h4>
              <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-yellow-400"></i>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                      ${app.action_message || 'Please provide additional information or documents for your application.'}
                    </p>
                  </div>
                </div>
              </div>
            `;
          } else {
            actionSection.innerHTML = '';
          }
          
          // Load documents
          loadDocuments(appId, appType);
          
          // Show the modal
          document.getElementById('applicationModal').classList.remove('hidden');
        } else {
          alert('Failed to load application details: ' + data.message);
        }
      } catch (error) {
        alert('Network error: ' + error.message);
      }
    }
    
    // Function to load documents for an application
    async function loadDocuments(appId, appType) {
      try {
        const response = await fetch(`api/get_documents.php?appId=${appId}&appType=${appType}`);
        const data = await response.json();
        
        const documentList = document.getElementById('documentList');
        
        if (data.success && data.documents.length > 0) {
          documentList.innerHTML = '<div class="space-y-2">' +
            data.documents.map(doc => `
              <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                <div>
                  <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                  <span>${doc.name}</span>
                </div>
                <a href="${doc.url}" class="text-nyeriGreen hover:text-green-800" download>
                  <i class="fas fa-download"></i>
                </a>
              </div>
            `).join('') + '</div>';
        } else {
          documentList.innerHTML = '<p class="text-gray-500">No documents uploaded yet.</p>';
        }
      } catch (error) {
        documentList.innerHTML = '<p class="text-red-500">Failed to load documents.</p>';
      }
    }
    
    // Function to open update modal
    function openUpdateModal(appId, appType) {
      document.getElementById('updateAppId').value = appId;
      document.getElementById('updateAppType').value = appType;
      document.getElementById('updateModal').classList.remove('hidden');
    }
    
    // Function to close update modal
    function closeUpdateModal() {
      document.getElementById('updateModal').classList.add('hidden');
      document.getElementById('updateForm').reset();
    }
    
    // Function to close modal
    function closeModal() {
      document.getElementById('applicationModal').classList.add('hidden');
    }
    
    // Function to show loading state
    function showLoading() {
      document.getElementById('loadingIndicator').classList.remove('hidden');
      document.getElementById('applicationsContainer').classList.add('hidden');
      document.getElementById('noApplications').classList.add('hidden');
    }
    
    // Function to hide loading state
    function hideLoading() {
      document.getElementById('loadingIndicator').classList.add('hidden');
    }
    
    // Function to show error message
    function showError(message) {
      hideLoading();
      alert(message);
    }
    
    function confirmLogout() {
      return confirm('Are you sure you want to log out?');
    }
    
    // Initialize the page
    document.addEventListener('DOMContentLoaded', function() {
      // Fetch applications on page load
      fetchApplications();
      
      // Set up event listeners
      document.getElementById('refreshBtn').addEventListener('click', fetchApplications);
      document.getElementById('applyFilters').addEventListener('click', renderApplications);
      document.getElementById('searchInput').addEventListener('input', renderApplications);
      document.getElementById('typeFilter').addEventListener('change', renderApplications);
      document.getElementById('statusFilter').addEventListener('change', renderApplications);
      
      // Set up form submission for file upload
      document.getElementById('uploadForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const statusDiv = document.getElementById('uploadStatus');
        
        try {
          statusDiv.classList.add('hidden');
          const response = await fetch('api/upload_document.php', {
            method: 'POST',
            body: formData
          });
          
          const data = await response.json();
          
          if (data.success) {
            statusDiv.className = 'mt-2 p-2 bg-green-100 text-green-700 rounded';
            statusDiv.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Document uploaded successfully!';
            statusDiv.classList.remove('hidden');
            
            // Reload documents
            loadDocuments(currentAppId, currentAppType);
            document.getElementById('uploadForm').reset();
          } else {
            throw new Error(data.message);
          }
        } catch (error) {
          statusDiv.className = 'mt-2 p-2 bg-red-100 text-red-700 rounded';
          statusDiv.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i>Error: ${error.message}`;
          statusDiv.classList.remove('hidden');
        }
      });
      
      // Set up form submission for application update
      document.getElementById('updateForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        try {
          const response = await fetch('api/update_application.php', {
            method: 'POST',
            body: formData
          });
          
          const data = await response.json();
          
          if (data.success) {
            alert('Application updated successfully!');
            closeUpdateModal();
            fetchApplications(); // Refresh the applications list
          } else {
            throw new Error(data.message);
          }
        } catch (error) {
          alert('Error updating application: ' + error.message);
        }
      });
      
      // Close modal when clicking outside
      document.getElementById('applicationModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
      });
      
      document.getElementById('updateModal').addEventListener('click', function(e) {
        if (e.target === this) closeUpdateModal();
      });
    });
  </script>
</body>
</html>