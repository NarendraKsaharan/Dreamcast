document.addEventListener('DOMContentLoaded', () => {
    const messageDiv = document.getElementById('dashboard-message');
    messageDiv.classList.add('flex', 'justify-center', 'items-center', 'h-screen');
    messageDiv.innerHTML = '<h1 class="text-4xl font-bold">Welcome to our Dashboard</h1>';

    const navLinks = document.querySelectorAll('.nav-link');
    
    const setActiveLink = (activeLink) => {
        navLinks.forEach(link => {
            link.classList.remove('active');
        });
        activeLink.classList.add('active');
    };

    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            setActiveLink(link);

            if (link.id === 'users-link') {
            // alert(2)
            userSection = document.querySelector('#user-section');
            permissionSection = document.querySelector('#permission-section');
            roleSection = document.querySelector('#role-section');
            messageDiv.classList.remove('flex', 'justify-center', 'items-center', 'h-screen');
            messageDiv.innerHTML = '';
            userSection.style.display = 'block';
            permissionSection.style.display = 'none';
            roleSection.style.display = 'none';
                const userForm = document.getElementById('userForm');
                const userTableBody = document.getElementById('user-table-body');
                const userModal = document.getElementById('add-user-modal');
                const updateModal = document.getElementById('user-update-modal');
                const addUserButton = document.getElementById('add-user-button');
                const closeModalButton = document.getElementById('close-modal');
                const closeUpdateModalButton = document.getElementById('close-update-modal');

            // Fetch users
            const fetchUsers = async () => {
                const response = await axios.get('/api/users');
                const users = response.data.users;
                const roles = response.data.roles;
                userTableBody.innerHTML = users.map(user => {
                    const roles = user.roles.map(role => role.name).join(', ');
                    // console.log(roles);
                return `
                    <tr>
                        <td class="border">${user.name}</td>
                        <td class="border">${user.email}</td>
                        <td class="border">${user.phone}</td>
                        <td class="border">${user.description}</td>
                        <td class="border">${roles ? roles : '-'}</td>
                        <td class="border">
                            ${user.profile_image ? `<img src="/storage/${user.profile_image}" class="h-16 w-16"/>` : ''}
                        </td>
                        <td class="border">
                            <button class="bg-yellow-500 text-white p-1 rounded update-button" data-id="${user.id}">Update</button>
                            <button class="bg-red-500 text-white p-1 rounded delete-button" data-id="${user.id}">Delete</button>
                        </td>
                    </tr>
                `}).join('');
                const rolesSelect = document.querySelector('select[name="roles[]"]');
                rolesSelect.innerHTML = roles.map(role => `
                    <option value="${role.name}">${role.name}</option>
                `).join('');
                
            };

            // Open add user modal
            addUserButton.addEventListener('click', () => {
                userModal.classList.remove('hidden');
            });

            // Close modals
            closeModalButton.addEventListener('click', () => {
                userModal.classList.add('hidden');
            });

            // update model
            closeUpdateModalButton.addEventListener('click', () => {
                updateModal.classList.add('hidden');
            });

            // Submit add user form
            userForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(userForm);
                await axios.post('/api/users', formData);
                userForm.reset();
                userModal.classList.add('hidden');
                fetchUsers();
            });

            // Open view modal
            userTableBody.addEventListener('click', async (e) => {

                // Open update modal
                if (e.target.classList.contains('update-button')) {
                    const userId = e.target.dataset.id;
                    const response = await axios.get(`/api/users/${userId}`);
                    // const user = response.data;
                    console.log(response.data);
                    const { user, roles} = response.data;

                    // Set data
                    document.getElementById('update_user_id').value = user.id;
                    document.getElementById('update_user_name').value = user.name;
                    document.getElementById('update_user_email').value = user.email;
                    document.getElementById('update_user_phone').value = user.phone;
                    document.getElementById('update_user_description').value = user.description;

                    const roleSelect = document.querySelector('select[name="role[]"]');
                    roleSelect.innerHTML = ''; 

                    roles.forEach(role => {
                        const isSelected = user.roles && user.roles.some(ur => ur.id === role.id);
                        const option = document.createElement('option');
                        option.value = role.name;
                        option.textContent = role.name;
                        option.selected = isSelected;
                        
                        roleSelect.appendChild(option);
                        console.log(option);
                    });
                  
                    updateModal.classList.remove('hidden');
                }
            });

            updateModal.addEventListener('submit', async (e) => {
                e.preventDefault();
                const userId = document.getElementById('update_user_id').value;

                const updateFormData = new FormData(updateModal.querySelector('form'));
                const data = {};
                for (let [key, value] of updateFormData.entries()) {
                    if (key === 'role[]') {
                        if (!data['role']) {
                            data['role'] = []; 
                        }
                        data['role'].push(value);
                    } else {
                        data[key] = value;
                    }
                }

                try {
                    await axios.put(`/api/users/${userId}`, data);
                    updateModal.classList.add('hidden');
                    fetchUsers();
                } catch (error) {
                    console.error(error.response.data); 
                }
            });

            // Delete user
            userTableBody.addEventListener('click', async (e) => {
                if (e.target.classList.contains('delete-button')) {
                    const userId = e.target.dataset.id;

                    if (confirm('Are you sure you want to delete this user?')) {
                        await axios.delete(`/api/users/${userId}`);
                        fetchUsers();
                    }
                }
            });
                fetchUsers();
                
            } else if (link.id === 'permission-link') {
                messageDiv.classList.remove('flex', 'justify-center', 'items-center', 'h-screen');
                messageDiv.innerHTML = '';
                userSection = document.querySelector('#user-section');
                permissionSection = document.querySelector('#permission-section');
                roleSection = document.querySelector('#role-section');
                permissionSection.style.display = 'block';
                userSection.style.display = 'none';
                roleSection.style.display = 'none';
                // alert(1)
                const permissionForm = document.getElementById('permissionForm');
                const permissionTableBody = document.getElementById('permission-table-body');
                const permissionModal = document.getElementById('permission-modal');
                const updatePermissionModal = document.getElementById('update-permission-modal');
                const addPermissionButton = document.getElementById('add-permission-button');
                // alert(addPermissionButton);
                const closeModalButton = document.getElementById('close-permission-modal');
                const closeUpdateModalButton = document.getElementById('close-update-permission--modal');

                // Fetch users
                const fetchPermissions = async () => {
                    const response = await axios.get('/api/permissions');
                    const permissions = response.data;
                    // console.log(permissions);
                    permissionTableBody.innerHTML = permissions.map(permission => `
                    <tr>
                        <td class="border text-center">${permission.name}</td>
                        <td class="border text-center flex justify-center space-x-2">
                            <button class="bg-yellow-500 text-white p-1 rounded update-button" data-id="${permission.id}">Update</button>
                            <button class="bg-red-500 text-white p-1 rounded delete-button" data-id="${permission.id}">Delete</button>
                        </td>
                    </tr>
                    `).join('');
                };

                // Open add permission modal
                addPermissionButton.addEventListener('click', () => {
                    permissionModal.classList.remove('hidden');
                });

                // // Close modals
                closeModalButton.addEventListener('click', () => {
                    permissionModal.classList.add('hidden');
                });

                // update model
                closeUpdateModalButton.addEventListener('click', () => {
                    updatePermissionModal.classList.add('hidden');
                });

                // // Submit add user form
                permissionForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const formData = new FormData(permissionForm);
                    await axios.post('/api/permissions', formData);
                    permissionForm.reset();
                    permissionModal.classList.add('hidden');
                    fetchPermissions();
                });

                // // Open view modal
                permissionTableBody.addEventListener('click', async (e) => {

                    // Open update modal
                    if (e.target.classList.contains('update-button')) {
                        const permissionId = e.target.dataset.id;
                        const response = await axios.get(`/api/permissions/${permissionId}`);
                        const permission = response.data;

                        // set update data
                        document.getElementById('update_permission_id').value = permission.id;
                        document.getElementById('update_permission_name').value = permission.name;

                        // Show update modal
                        updatePermissionModal.classList.remove('hidden');
                    }
                });

                updatePermissionModal.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const permissionId = document.getElementById('update_permission_id').value;

                    const updateFormData = new FormData(updatePermissionModal.querySelector('form'));
                    const data = {};
                    for (let [key, value] of updateFormData.entries()) {
                        // console.log(key, value);
                        data[key] = value;
                    }

                    try {
                        await axios.put(`/api/permissions/${permissionId}`, data);
                        updatePermissionModal.classList.add('hidden');
                        fetchPermissions();
                    } catch (error) {
                        alert(error);
                        console.error(error.response.data);
                    }
                });

                // Delete permission
                permissionTableBody.addEventListener('click', async (e) => {
                    if (e.target.classList.contains('delete-button')) {
                        const permissionId = e.target.dataset.id;

                        if (confirm('Are you sure you want to delete this permission?')) {
                            await axios.delete(`/api/permissions/${permissionId}`);
                            fetchPermissions();
                        }
                    }
                });

                fetchPermissions(); 
            } else if (link.id === 'role-link') {
                messageDiv.classList.remove('flex', 'justify-center', 'items-center', 'h-screen');
                messageDiv.innerHTML = '';
                userSection = document.querySelector('#user-section');
                permissionSection = document.querySelector('#permission-section');
                roleSection = document.querySelector('#role-section');
                roleSection.style.display = 'block';
                userSection.style.display = 'none';
                permissionSection.style.display = 'none';
                // alert(1)
                const roleForm = document.getElementById('roleForm');
                const roleTableBody = document.getElementById('role-table-body');
                const roleModal = document.getElementById('role-modal');
                const updateRoleModal = document.getElementById('update-role-modal');
                const addRoleButton = document.getElementById('add-role-button');
                const closeModalButton = document.getElementById('close-role-modal');
                const closeUpdateModalButton = document.getElementById('close-update-role-modal');

                // Fetch users
                const fetchRoles = async () => {
                    const response = await axios.get('/api/roles');
                    // const roles = response.data;
                    const roles = response.data.roles;
                    const permission = response.data.permissions;
                    // console.log(permission);
                    roleTableBody.innerHTML = roles.map(role => {
                        const permissions = role.permissions.map(permission => permission.name).join(', ');
                        return `
                            <tr>
                                <td class="border text-center">${role.name}</td>
                                <td class="border text-center">${permissions}</td>
                                <td class="border text-center flex justify-center space-x-2">
                                    <button class="bg-yellow-500 text-white p-1 rounded update-button" data-id="${role.id}">Update</button>
                                    <button class="bg-red-500 text-white p-1 rounded delete-button" data-id="${role.id}">Delete</button>
                                </td>
                            </tr>
                        `;
                    }).join('');

                    const permissionsSelect = document.querySelector('select[name="permissions[]"]');
                    permissionsSelect.innerHTML = permission.map(per => `
                        <option value="${per.name}">${per.name}</option>
                    `).join('');
                };

                // Open add role modal
                addRoleButton.addEventListener('click', () => {
                    roleModal.classList.remove('hidden');
                });

                // Close modals
                closeModalButton.addEventListener('click', () => {
                    roleModal.classList.add('hidden');
                });

                closeUpdateModalButton.addEventListener('click', () => {
                    updateRoleModal.classList.add('hidden');
                });

                // Submit add user form
                roleForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const formData = new FormData(roleForm);
                    await axios.post('/api/roles', formData);
                    roleForm.reset();
                    roleModal.classList.add('hidden');
                    fetchRoles();
                });

                // Open update modal
                roleTableBody.addEventListener('click', async (e) => {
                    if (e.target.classList.contains('update-button')) {
                        const roleId = e.target.dataset.id;

                        try {
                            const response = await axios.get(`/api/roles/${roleId}`);
                            const { role, permissions } = response.data;

                            // set role data
                            document.getElementById('update_role_id').value = role.id;
                            document.getElementById('update_role_name').value = role.name;

                            const permissionSelect = document.querySelector('select[name="permission[]"]');
                            permissionSelect.innerHTML = ''; 

                            permissions.forEach(permission => {
                                const isSelected = role.permissions && role.permissions.some(rp => rp.id === permission.id);
                                const option = document.createElement('option');
                                option.value = permission.name;
                                option.textContent = permission.name;
                                option.selected = isSelected;
                                
                                permissionSelect.appendChild(option);
                                // console.log(option);
                            });


                            updateRoleModal.classList.remove('hidden');
                        } catch (error) {
                            console.error('Error fetching role data:', error);
                        }
                    }
                });

                updateRoleModal.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const roleId = document.getElementById('update_role_id').value;

                    const updateFormData = new FormData(updateRoleModal.querySelector('form'));
                    const data = {};
                    for (let [key, value] of updateFormData.entries()) {
                        if (key === 'permission[]') {
                            if (!data['permission']) {
                                data['permission'] = []; 
                            }
                            data['permission'].push(value);
                        } else {
                            data[key] = value;
                        }
                    }
                    // console.log(data);

                    try {
                        const response = await axios.put(`/api/roles/${roleId}`, data);
                        console.log('response:', response.data); 
                        updateRoleModal.classList.add('hidden');
                        fetchRoles();
                    } catch (error) {
                        alert(error);
                        console.error(error.response.data);
                    }
                });

                // Delete role
                roleTableBody.addEventListener('click', async (e) => {
                    if (e.target.classList.contains('delete-button')) {
                        const roleId = e.target.dataset.id;

                        if (confirm('Are you sure you want to delete this role?')) {
                            await axios.delete(`/api/roles/${roleId}`);
                            fetchRoles();
                        }
                    }
                });

                fetchRoles(); 
            }
        });
    });
});