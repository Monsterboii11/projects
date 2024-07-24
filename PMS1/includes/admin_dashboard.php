<?php
session_start();
include 'connection.php'; // Ensure this file is correctly included and connects to the database
include 'dynamic_role_title.php'; // Include file for dynamic title based on user role
include 'dynamic_task.php'; // Include file to fetch task count
include "dynamic_project.php"; // Include file to get filtered project list

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>User Dashboard</title>
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="../CSS/user_dashboard.css">
</head>

<body>
	<!-- SIDEBAR -->
	<section id="sidebar">
		<a class="brand" href="#">
			<i class="bx bxs-smile"></i>
			<span class="text"><?php echo $greeting; ?></span>
		</a>
		<ul class="side-menu top">
			<li class="active">
				<a href="#">
					<i class='bx bxs-dashboard'></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="projects.php">
					<i class='bx bxs-objects-horizontal-right'></i>
					<span class="text">Projects</span>
				</a>
			</li>
			<li>
				<a href="task.php">
					<i class='bx bx-task'></i>
					<span class="text">Tasks</span>
				</a>
			</li>
			<!-- Users link (only visible to admin) -->
			<?php if ($_SESSION['user_role'] == 1) : ?>
				<li>
					<a href="users.php">
						<i class='bx bxs-group'></i>
						<span class="text">Users</span>
					</a>
				</li>
			<?php endif; ?>
		</ul>
		<ul class="side-menu">
			<li>
				<a href="profile.php" class="settings-link">
					<i class='bx bxs-user'></i>
					<span class="text">Profile</span>
				</a>
			</li>
			<li>
				<a href="user_login.php" class="logout">
					<i class='bx bxs-log-out-circle'></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->

	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu'></i>
			<a href="#" class="nav-link">Dashboard</a>
			<form id="search-form" action="search.php" method="POST">
				<div class="form-input">
					<input type="search" placeholder="Search...">
					<button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
				</div>
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label>
			<!-- <a href="#" class="notification">
				<i class='bx bxs-bell'></i>
				<span class="num">8</span>
			</a> -->
			<a href="profile.php" class="profile">
				<i class='bx bxs-user' style='font-size: 1.5em;'></i>
			</a>

		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Welcome, <?php echo $greeting; ?>!</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right'></i></li>
						<li>
							<a class="active" href="#">Home</a>
						</li>
					</ul>
				</div>
				<!-- <a href="#" class="btn-download">
                    <i class='bx bxs-cloud-download'></i>
                    <span class="text">Download PDF</span>
                </a> -->
			</div>

			<ul class="box-info">
				<li>
					<i class='bx bxs-objects-vertical-center'></i>
					<span class="text">
						<h3><?php include 'get_project_count.php'; ?></h3>
						<p>projects</p>
					</span>
				</li>
				<?php if ($_SESSION['user_role'] == 1) : ?>
					<?php
					// Assuming you have a connection to the database and want to fetch the total number of users
					include "connection.php";

					// Query to count total users
					$sql = "SELECT COUNT(*) as total_users FROM users";
					$result = $conn->query($sql);

					if ($result && $result->num_rows > 0) {
						$row = $result->fetch_assoc();
						$total_users = $row['total_users'];
					} else {
						$total_users = 0; // Default value if query fails or no users found
					}

					// Close database connection
					$conn->close();
					?>
					<li>
						<i class='bx bxs-group'></i>
						<span class="text">
							<h3><?php echo $total_users; ?></h3>
							<p>Total Users</p>
						</span>
					</li>
				<?php endif; ?>
				<li>
					<i class='bx bx-task'></i>
					<span class="text">
						<?php
						// Check the user's role
						if ($_SESSION['user_role'] == 1) {
							// Admin view - tasks given
							echo '<h3>' . $task_count . '</h3>';
							echo '<p>Tasks Given</p>';
						} elseif ($_SESSION['user_role'] == 2) {
							// User view - tasks received
							echo '<h3>' . $task_count . '</h3>';
							echo '<p>Tasks Received</p>';
						}
						?>
					</span>
				</li>
			</ul>
			<div class="table-data">
				<div class="order">
					<table>
						<thead>
							<tr>
								<th>S.no</th>
								<th>Project Name</th>
								<th>Start Date</th>
								<th>End Date</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							while ($row = $projectResult->fetch_assoc()) {
							?>
								<tr id="projectRow<?php echo $row['id']; ?>" class="project-row">
									<td><?php echo $count; ?></td>
									<td><?php echo $row['project_name']; ?></td>
									<td><?php echo $row['start_date']; ?></td>
									<td><?php echo $row['end_date']; ?></td>
									<td>
										<span class="status <?php echo strtolower($row['status']); ?>">
											<?php echo $row['status']; ?>
										</span>
									</td>
								</tr>
							<?php
								$count++;
							}
							?>
						</tbody>
					</table>
				</div>

				<div class="todo">
					<div class="head">
						<h3>Todos</h3>
					</div>
					<ul class="todo-list">
						<?php
						include "dynamic_role_title.php";
						include "connection.php";

						// Check if user is logged in
						if (!isset($_SESSION['user_id'])) {
							header("Location: user_login.php");
							exit();
						}

						// Fetch user's tasks based on user_role and user_id
						$user_id = $_SESSION['user_id'];
						$user_role = $_SESSION['user_role'];

						// Modify your SQL query to fetch tasks assigned to the logged-in user
						if ($user_role == 1) {
							$sql = "SELECT * FROM task_list";
						} else {
							$sql = "SELECT * FROM task_list WHERE user_id = $user_id";
						}

						$resultTodo = $conn->query($sql);
						// Loop through $resultTodo and display tasks
						while ($row = $resultTodo->fetch_assoc()) {
							// Display task details
							echo "<li class='completed'>";
							echo "<p>" . $row['description'] . "</p>";
							echo "<div class='action-icons'>";
							echo "<i class='bx bx-dots-vertical-rounded action-icon' data-task-id='" . $row['id'] . "'></i>";
							echo "<div class='dropdown-menu' id='dropdownMenu" . $row['id'] . "'>";
							echo "<a class='dropdown-item' href='edit_task.php?id=" . $row['id'] . "'>Edit</a>";
							echo "<a class='dropdown-item' href='delete_task.php?delete_id=" . $row['id'] . "'>Delete</a>";
							echo "</div>";
							echo "</div>";
							echo "</li>";
						}
						$conn->close();
						?>

					</ul>
				</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<!-- jQuery must be loaded before any jQuery-dependent code -->
	<script src="../JS/script.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script>
		$(document).ready(function() {
			$('.project-row').click(function() {
				window.location.href = 'projects.php?id=';
			});
		});
	</script>

</body>

</html>