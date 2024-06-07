//Click events
document.getElementById('admin-portal').addEventListener('click', function(){
    window.location.href = '/dealer-portal/pages/admin/admin.php'
});

document.getElementById('website').addEventListener('click', function(){
    window.location.href = '/dealer-portal/index.php'
});

// User details fetch
document.getElementById('selected_user_id').addEventListener('change', function() {
    var userId = this.value;
    if (userId) {
        fetch('/dealer-portal/pages/admin/update-user.php?user_id=' + userId)
        .then(response => response.json())
        .then(data => {
            document.getElementById('username').value = data.username;
            document.getElementById('email').value = data.email;
            document.getElementById('password').value = "";
            document.getElementById('first_name').value = data.first_name;
            document.getElementById('last_name').value = data.last_name;
            document.getElementById('address').value = data.address;
            document.getElementById('phone').value = data.phone;
            document.getElementById('is_admin').checked = data.is_admin == 1;
        })
        .catch(error => console.error('Error:', error));
    } else {
        document.getElementById('updateUserForm').reset();
    }
});



