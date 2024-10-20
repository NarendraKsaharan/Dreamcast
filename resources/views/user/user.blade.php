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

<div id="dashboard-message"></div>

<div class="container mx-auto mt-32" id="user-section" style="display: none">
    <h1 class="text-2xl mb-4">User Management</h1>
    <button id="add-user-button" class="bg-blue-500 text-white p-2 rounded">Add User</button>

    <table class="min-w-full mt-4 border">
        <thead>
            <tr>
                <th class="border">Name</th>
                <th class="border">Email</th>
                <th class="border">Phone</th>
                <th class="border">Description</th>
                <th class="border">Roles</th>
                <th class="border">Profile Image</th>
                <th class="border">Actions</th>
            </tr>
        </thead>
        <tbody id="user-table-body"></tbody>
    </table>
</div>

<!-- Add User Modal -->
<div id="add-user-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden  max-h-[80vh] overflow-y-auto mt-16">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h2 class="text-xl mb-4">Add User</h2>
        <form id="userForm" enctype="multipart/form-data">
            <div id="error-message" class="text-red-500 mb-2"></div>
            <input type="text" name="name" placeholder="Name" required class="border p-2 mb-2 w-full">
            <input type="email" name="email" placeholder="Email" required class="border p-2 mb-2 w-full">
            <input type="text" name="phone" placeholder="Phone" id="phone-error" required class="border p-2 mb-2 w-full">
            <textarea name="description" placeholder="Description" class="border p-2 mb-2 w-full"></textarea>
            <select name="roles[]" required class="border p-2 mb-2 w-full" multiple></select>
            <input type="file" name="profile_image" class="border mb-2 w-full">
            <div class="flex justify-between">
                <button type="button" id="close-modal" class="bg-gray-300 text-black p-2 rounded">Cancel</button>
                <button type="submit" class="bg-green-500 text-white p-2 rounded">Submit</button>
            </div>
        </form>
    </div>
</div>



<!-- Update User Modal -->
<div id="user-update-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden max-h-[80vh] overflow-y-auto mt-16">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h2 class="text-xl mb-4">Update User</h2>
        <form id="updateForm">
            <input type="hidden" name="user_id" id="update_user_id">
            <input type="text" name="name" id="update_user_name" placeholder="Name" required class="border p-2 mb-2 w-full">
            <input type="email" name="email" id="update_user_email" placeholder="Email" required class="border p-2 mb-2 w-full">
            <input type="text" name="phone" id="update_user_phone" placeholder="Phone" required class="border p-2 mb-2 w-full">
            <textarea name="description" id="update_user_description" placeholder="Description" class="border p-2 mb-2 w-full"></textarea>
            <select name="role[]" required class="border p-2 mb-2 w-full" multiple></select>
            <div class="flex justify-between">
                <button type="button" id="close-update-modal" class="bg-gray-300 text-black p-2 rounded">Cancel</button>
                <button type="submit" class="bg-green-500 text-white p-2 rounded">Update</button>
            </div>
        </form>        
    </div>
</div>


{{-- Permission section --}}
<div class="container mx-auto mt-32" id="permission-section" style="display: none">
    <h1 class="text-2xl mb-4">Permission Management</h1>
    <button id="add-permission-button" class="bg-blue-500 text-white p-2 rounded">Add Permission</button>

    <table class="min-w-full mx-auto mt-4 border" style="width: 50%">
        <thead>
            <tr>
                <th class="border w-1/2">Name</th>
                <th class="border w-1/2">Actions</th>
            </tr>
        </thead>
        <tbody id="permission-table-body"></tbody>
    </table>
</div>

<!-- Add permission Modal -->
<div id="permission-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden max-h-[80vh] overflow-y-auto mt-16">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h2 class="text-xl mb-4">Add Permission</h2>
        <form id="permissionForm">
            <input type="text" name="name" placeholder="Name" required class="border p-2 mb-2 w-full">
            <div class="flex justify-between">
                <button type="button" id="close-permission-modal" class="bg-gray-300 text-black p-2 rounded">Cancel</button>
                <button type="submit" class="bg-green-500 text-white p-2 rounded">Submit</button>
            </div>
        </form>
    </div>
</div>


<!-- Update permission Modal -->
<div id="update-permission-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden max-h-[80vh] overflow-y-auto mt-16">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h2 class="text-xl mb-4">Update Permission</h2>
        <form id="updateForm">
            <input type="hidden" name="permission_id" id="update_permission_id">
            <input type="text" name="name" id="update_permission_name" placeholder="Name" required class="border p-2 mb-2 w-full">
            <div class="flex justify-between">
                <button type="button" id="close-update-permission--modal" class="bg-gray-300 text-black p-2 rounded">Cancel</button>
                <button type="submit" class="bg-green-500 text-white p-2 rounded">Update</button>
            </div>
        </form>        
    </div>
</div>


{{-- Role section --}}
<div class="container mx-auto mt-32" id="role-section" style="display: none">
    <h1 class="text-2xl mb-4">Role Management</h1>
    <button id="add-role-button" class="bg-blue-500 text-white p-2 rounded">Add Role</button>

    <table class="min-w-full mx-auto mt-4 border" style="width: 50%">
        <thead>
            <tr>
                <th class="border w-1/2">Name</th>
                <th class="border w-1/2">Actions</th>
            </tr>
        </thead>
        <tbody id="role-table-body"></tbody>
    </table>
</div>

<!-- Add role Modal -->
<div id="role-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden max-h-[80vh] overflow-y-auto mt-16">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h2 class="text-xl mb-4">Add Role</h2>
        <form id="roleForm">
            <input type="text" name="name" placeholder="Name" required class="border p-2 mb-2 w-full">
            <select name="permissions[]" required class="border p-2 mb-2 w-full" multiple>
                <!-- Options will be populated here -->
            </select>
            <div class="flex justify-between">
                <button type="button" id="close-role-modal" class="bg-gray-300 text-black p-2 rounded">Cancel</button>
                <button type="submit" class="bg-green-500 text-white p-2 rounded">Submit</button>
            </div>
        </form>
    </div>
</div>


<!-- Update Role Modal -->
<div id="update-role-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden max-h-[80vh] overflow-y-auto mt-16">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h2 class="text-xl mb-4">Update Role</h2>
        <form id="updateForm">
            <input type="hidden" name="role_id" id="update_role_id">
            <input type="text" name="name" id="update_role_name" placeholder="Name" required class="border p-2 mb-2 w-full">
            <select name="permission[]" required class="border p-2 mb-2 w-full" multiple></select>
            <div class="flex justify-between">
                <button type="button" id="close-update-role-modal" class="bg-gray-300 text-black p-2 rounded">Cancel</button>
                <button type="submit" class="bg-green-500 text-white p-2 rounded">Update</button>
            </div>
        </form>        
    </div>
</div>


</body>
</html>
