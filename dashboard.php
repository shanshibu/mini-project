<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BooksMart Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-dark border-right" id="sidebar-wrapper">
        <div class="sidebar-heading text-white text-center py-4">BooksMart Admin</div>
        <div class="list-group list-group-flush">
            <a href="#overview" class="list-group-item list-group-item-action bg-dark text-white text-center">Dashboard Overview</a>
            <a href="#user-management" class="list-group-item list-group-item-action bg-dark text-white text-center">User Management</a>
            <a href="#payment-details" class="list-group-item list-group-item-action bg-dark text-white text-center">Payment Details</a>
        </div>
    </div>

        <!-- Page Content -->
        <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary border-bottom">
            <span class="navbar-brand ml-auto">Admin Dashboard</span>
            <a href="logout.php" class="btn btn-danger ml-2">Logout</a>
        </nav>

        <div class="container-fluid p-4" id="overview">
        <h1 class="mt-4">Dashboard Overview</h1>
                <div class="row">
                    <!-- Total Users Card -->
                    <div class="col-lg-6 col-md-12">
                        <div class="card bg-info text-white mb-4">
                            <div class="card-body">Total Users</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <span class="font-weight-bold">
                                    <?php
                                    // Database connection
                                    $conn = new mysqli("127.0.0.1:3307", "root", "", "project");

                                    if ($conn->connect_error) {
                                        die("Connection failed: " . $conn->connect_error);
                                    }

                                    // Get total users count
                                    $sql = "SELECT COUNT(*) AS total_users FROM login";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();
                                    echo $row['total_users'];
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Books Card -->
                    <div class="col-lg-6 col-md-12">
                        <div class="card bg-success text-white mb-4">
                            <div class="card-body">Available Books</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <span class="font-weight-bold">
                                    <?php
                                    // Get total books count
                                    $sql = "SELECT COUNT(*) AS total_books FROM sellings";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();
                                    echo $row['total_books'];
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pie Chart Section -->
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Books Category</h5>
                                <div style="max-width: 300px; margin: auto;">
                                    <canvas id="userTypeChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cards for Books Sold and Books Rented -->
                    <div class="col-lg-6 col-md-12">
                        <div class="row">
<!-- Books Sold Card -->
<div class="col-md-12 mb-4">
    <div class="card bg-warning text-white">
        <div class="card-body">Books Sold</div>
        <div class="card-footer d-flex align-items-center justify-content-between">
            <span class="font-weight-bold">
                <?php
                // Get books sold count
                $sql = "SELECT COUNT(*) AS books_sold FROM payment WHERE transaction_type = 'sell'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                echo $row['books_sold'];
                ?>
            </span>
        </div>
    </div>
</div>

<!-- Books Rented Card -->
<div class="col-md-12 mb-4">
    <div class="card bg-info text-white">
        <div class="card-body">Books Rented</div>
        <div class="card-footer d-flex align-items-center justify-content-between">
            <span class="font-weight-bold">
                <?php
                // Get books rented count
                $sql = "SELECT COUNT(*) AS books_rented FROM payment WHERE transaction_type = 'rent'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                echo $row['books_rented'];
                ?>
            </span>
        </div>
    </div>
</div>

                        </div>
                    </div>
                </div>
<!-- User Management Section -->
<div id="user-management">
    <h2>User Management</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Database connection
            $conn = new mysqli("127.0.0.1:3307", "root", "", "project");

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch user data
            $sql = "SELECT id, username, email FROM login";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr id='row-" . $row["id"] . "'>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td id='username-" . $row["id"] . "'>" . $row["username"] . "</td>";
                    echo "<td id='email-" . $row["id"] . "'>" . $row["email"] . "</td>";
                    echo "<td>
                        <button onclick='editUser(" . $row["id"] . ")' class='btn btn-warning btn-sm'>Edit</button>
                        <button onclick='deleteUser(" . $row["id"] . ")' class='btn btn-danger btn-sm'>Delete</button>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No users found</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</div>


<div id="payment-details" class="container mt-4">
    <h2 class="text-center mb-4">Payment Details</h2>
    <table class="table table-bordered table-striped table-sm">
        <thead class="table-dark">
            <tr>
                <th>Customer Name</th>
                <th>Book Name</th>
                <th>Price</th>
                <th>Transaction Type</th>
                <th>Payment Method</th>
                <th>Date and Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Database connection
            $conn = new mysqli("127.0.0.1:3307", "root", "", "project");

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch payment data
            $sql = "SELECT customer_name, book_name, price, transaction_type, payment_method, created_at FROM payment";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["customer_name"] . "</td>";
                    echo "<td>" . $row["book_name"] . "</td>";
                    echo "<td>₹" . $row["price"] . "</td>";
                    echo "<td>" . $row["transaction_type"] . "</td>";
                    echo "<td>" . $row["payment_method"] . "</td>";
                    echo "<td>" . $row["created_at"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No payment records found</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</div>

        </div>
    </div>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>


        // PHP to get user type counts
        <?php
            $conn = new mysqli("127.0.0.1:3307", "root", "", "project");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $userTypeCounts = ["student" => 0, "professional" => 0, "casuals" => 0];
            $sql = "SELECT user_type, COUNT(*) as count FROM sellings GROUP BY user_type";
            $result = $conn->query($sql);

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $userTypeCounts[$row['user_type']] = $row['count'];
                }
            }

            $conn->close();
        ?>

        // Data for Chart.js
        const userTypeData = {
            labels: ["Students", "Professionals", "Casuals"],
            datasets: [{
                data: [
                    <?php echo $userTypeCounts['student']; ?>,
                    <?php echo $userTypeCounts['professional']; ?>,
                    <?php echo $userTypeCounts['casuals']; ?>
                ],
                backgroundColor: ['#36A2EB', '#FFCE56', '#FF6384']
            }]
        };

        // Chart configuration
        const config = {
            type: 'pie',
            data: userTypeData,
            options: {
                responsive: true,
                maintainAspectRatio: true, // Keep chart size proportional
                aspectRatio: 1, // 1 means square; adjust to make it smaller or larger
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        };

        // Render the chart
        window.onload = function() {
            const ctx = document.getElementById('userTypeChart').getContext('2d');
            new Chart(ctx, config);
        };
        // Function to enable editing
function editUser(id) {
    // Get current username and email
    let username = document.getElementById('username-' + id).innerText;
    let email = document.getElementById('email-' + id).innerText;

    // Replace cells with input fields
    document.getElementById('username-' + id).innerHTML = `<input type='text' id='edit-username-${id}' value='${username}'>`;
    document.getElementById('email-' + id).innerHTML = `<input type='text' id='edit-email-${id}' value='${email}'>`;

    // Replace Edit button with Save and Cancel
    document.getElementById('row-' + id).querySelector('td:last-child').innerHTML = `
        <button onclick='saveUser(${id})' class='btn btn-success btn-sm'>Save</button>
        <button onclick='cancelEdit(${id}, "${username}", "${email}")' class='btn btn-secondary btn-sm'>Cancel</button>
    `;
}

// Function to save edited user data
function saveUser(id) {
    let username = document.getElementById('edit-username-' + id).value;
    let email = document.getElementById('edit-email-' + id).value;

    // AJAX request to update user data
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "edit_user.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Update table with new values
            document.getElementById('username-' + id).innerText = username;
            document.getElementById('email-' + id).innerText = email;
            document.getElementById('row-' + id).querySelector('td:last-child').innerHTML = `
                <button onclick='editUser(${id})' class='btn btn-warning btn-sm'>Edit</button>
                <button onclick='deleteUser(${id})' class='btn btn-danger btn-sm'>Delete</button>
            `;
        }
    };
    xhr.send(`id=${id}&username=${username}&email=${email}`);
}

// Function to cancel edit and revert to original data
function cancelEdit(id, username, email) {
    document.getElementById('username-' + id).innerText = username;
    document.getElementById('email-' + id).innerText = email;
    document.getElementById('row-' + id).querySelector('td:last-child').innerHTML = `
        <button onclick='editUser(${id})' class='btn btn-warning btn-sm'>Edit</button>
        <button onclick='deleteUser(${id})' class='btn btn-danger btn-sm'>Delete</button>
    `;
}

// Function to delete a user
function deleteUser(id) {
    if (confirm("Are you sure you want to delete this user?")) {
        window.location.href = 'delete_user.php?id=' + id;
    }
}


    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="dashboard.js"></script>
</body>
</html>
