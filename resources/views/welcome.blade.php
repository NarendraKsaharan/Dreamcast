<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
    <style>
        .active {
            color: #ef4d02; 
            font-weight: bold;
        }
    </style>
</head>
<body class="antialiased">
@include('header')

<div class="container mx-auto mt-16">
    <h1 class="text-2xl mb-4">User Management</h1>
    <button id="add-user-button" class="bg-blue-500 text-white p-2 rounded">Add User</button>

    <table class="min-w-full mt-4 border">
        <thead>
            <tr>
                <th class="border">Name</th>
                <th class="border">Email</th>
                <th class="border">Phone</th>
                <th class="border">Description</th>
                <th class="border">Role ID</th>
                <th class="border">Profile Image</th>
            </tr>
        </thead>
        <tbody id="user-table-body"></tbody>
    </table>
</div>

<!-- Modal -->
<div id="user-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h2 class="text-xl mb-4">Add User</h2>
        <form id="userForm" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Name" required class="border p-2 mb-2 w-full">
            <input type="email" name="email" placeholder="Email" required class="border p-2 mb-2 w-full">
            <input type="text" name="phone" placeholder="Phone" required class="border p-2 mb-2 w-full">
            <textarea name="description" placeholder="Description" class="border p-2 mb-2 w-full"></textarea>
            <input type="number" name="role_id" placeholder="Role ID" required class="border p-2 mb-2 w-full">
            <input type="file" name="profile_image" class="border mb-2 w-full">
            <div class="flex justify-between">
                <button type="button" id="close-modal" class="bg-gray-300 text-black p-2 rounded">Cancel</button>
                <button type="submit" class="bg-green-500 text-white p-2 rounded">Submit</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const userForm = document.getElementById('userForm');
    const userTableBody = document.getElementById('user-table-body');
    const userModal = document.getElementById('user-modal');
    const addUserButton = document.getElementById('add-user-button');
    const closeModalButton = document.getElementById('close-modal');
    const usersLink = document.getElementById('users-link');

    addUserButton.addEventListener('click', () => {
        userModal.classList.remove('hidden');
    });

    closeModalButton.addEventListener('click', () => {
        userModal.classList.add('hidden');
    });

    // Fetch users
    const fetchUsers = async () => {
        const response = await axios.get('/api/users');
        const users = response.data;
        userTableBody.innerHTML = users.map(user => `
            <tr>
                <td class="border">${user.name}</td>
                <td class="border">${user.email}</td>
                <td class="border">${user.phone}</td>
                <td class="border">${user.description}</td>
                <td class="border">${user.role_id}</td>
                <td class="border">
                    ${user.profile_image ? `<img src="/storage/${user.profile_image}" class="h-16 w-16"/>` : ''}
                </td>
            </tr>
        `).join('');
    };

    // Submit the form
    userForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(userForm);
        await axios.post('/api/users', formData);
        userForm.reset();
        userModal.classList.add('hidden');
        fetchUsers();
    });

    // fetchUsers(); // Initial fetch of users
    // Handle Users link click
    fetchUsers(); 
    usersLink.addEventListener('click', (e) => {
        e.preventDefault(); 
        fetchUsers(); 
    });
});
</script>
</body>
</html>
