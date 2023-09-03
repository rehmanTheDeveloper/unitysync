<?php

// Sanitize Data
function sanitize($conn, $value)
{
	$value = trim($value);
	$value = stripslashes($value);
	$value = htmlspecialchars($value);
	$value = mysqli_real_escape_string($conn, $value);
	return $value;
}
// Sanitize Data

// Selected Table ID
function selectedTableId($conn, $table, $column, $id, $count)
{
	$key = '';
	$query = "SELECT * FROM `$table` ORDER BY id DESC LIMIT 1 WHERE `project_id` = '" . $_SESSION['project'] . "';";
	$sql = mysqli_query($conn, $query);
	$row = mysqli_fetch_assoc($sql);
	if (mysqli_num_rows($sql) > 0) {
		$key = intval(substr($row[$column], $count)) + 1;
		return $id . '-' . $key;
	} else {
		return $id . '-1';
	}
}
// Selected Table ID

// Voucher ID
function ledgerVoucherId($conn)
{
	$numbers = "0123456789";
	$random = "";

	// Generate a random 10-digit number
	for ($i = 0; $i < 8; $i++) {
		$random .= $numbers[mt_rand(0, strlen($numbers) - 1)];
	}

	// Check if the generated voucher ID exists in the database
	$query = "SELECT COUNT(*) AS count FROM ledger WHERE `v-id` = ? AND `project_id` = '" . $_SESSION['project'] . "';";
	$stmt = mysqli_prepare($conn, $query);

	if ($stmt === false) {
		die("MySQLi prepare error: " . mysqli_error($conn));
	}

	$stmt->bind_param("s", $random);
	$stmt->execute();
	$stmt->bind_result($count);
	$stmt->fetch();
	$stmt->close();

	if ($count === 0) {
		return $random; // The voucher ID is unique
	} else {
		// If not unique, generate a new one recursively
		return ledgerVoucherId($conn);
	}
}
// Voucher ID

// Number Format
function number_format_thousands($n, $precision = 2)
{
	if ($n < 900) {
		// 0 - 900
		$n_format = number_format($n, $precision);
		$suffix = '';
	} else if ($n < 900000) {
		// 0.9k-850k
		$n_format = number_format($n / 1000, $precision);
		$suffix = 'K';
	} else if ($n < 900000000) {
		// 0.9m-850m
		$n_format = number_format($n / 1000000, $precision);
		$suffix = 'M';
	} else if ($n < 900000000000) {
		// 0.9b-850b
		$n_format = number_format($n / 1000000000, $precision);
		$suffix = 'B';
	} else {
		// 0.9t+
		$n_format = number_format($n / 1000000000000, $precision);
		$suffix = 'T';
	}
	if ($precision > 0) {
		$dotzero = '.' . str_repeat('0', $precision);
		$n_format = str_replace($dotzero, '', $n_format);
	}
	return $n_format . $suffix;
}
// Number Format

// Fetch Data From Database
function fetch_Data($conn, $query)
{
	$sql = mysqli_query($conn, $query);
	if (mysqli_num_rows($sql) > 0) {
		while ($row = mysqli_fetch_assoc($sql)) {
			$return[] = $row;
		}
		return $return;
	} else {
		return "";
	}
}
// Fetch Data From Database

// Role Validation
function validationRole($DatabaseConnection, $project, $userRole, $hasPermission)
{
	// $permissionId = "";
	// $permission_boolean = false;
	// $query = "SELECT `id` FROM `roles` WHERE `name` = '$userRole' AND `project_id` = '".$project."';";
	// $role_id = mysqli_fetch_assoc(mysqli_query($DatabaseConnection, $query));
	// $permissions = explode(", ", $hasPermission);
	// foreach ($permissions as $value) {
	// 	$query = "SELECT `id` FROM `role_permissions` WHERE `role` = ? AND `permission` = ? AND `project_id` = '".$project."';";
	// 	$RP = $DatabaseConnection->prepare($query);
	// 	$RP->bind_param("ss", $role_id['id'], $value);
	// 	$RP->execute();
	// 	$RP->bind_result($permissionId);
	// 	$RP->fetch();
	// 	if (!empty($permissionId)) {
	// 		$permission_boolean = true;
	// 		return $permission_boolean;
	// 	}
	// }
	return TRUE;
}
// Role Validation

// Activity Record
function activity($conn, $activity)
{
	$query = "INSERT INTO `activity`(`date`, `message`, `UI`, `project_id`, `created_date`, `created_by`) VALUES (?,?,?,?,?,?);";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("ssssss", $activity['date'], $activity['activity'], $activity['user_id'], $activity['project'], $activity['created_date'], $activity['created_by']);
	$stmt->execute();
}
// Activity Record

// Ledger Record
function ledger($conn, $ledger)
{
	$query = "INSERT INTO `ledger`(`v-id`, `type`, `source`, `remarks`";
	if (!empty($ledger['credit'])) {
		$query .= ", `credit`";
		$ledger['amount'] = $ledger['credit'];
		unset($ledger['credit']);
		unset($ledger['debit']);
	} else {
		$query .= ", `debit`";
		$ledger['amount'] = $ledger['debit'];
		unset($ledger['credit']);
		unset($ledger['debit']);
	}
	$query .= ", `project_id`, `created_date`, `created_by`) VALUES (?,?,?,?,?,?,?,?);";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("ssssssss", $ledger['v-id'], $ledger['type'], $ledger['source'], $ledger['remarks'], $ledger['amount'], $ledger['project'], $ledger['created_date'], $ledger['created_by']);
	// print_r($ledger);
	$stmt->execute();
}
// Ledger Record

// Sum of selected Index
function getSumOfId($array, $id)
{
	$sumOfId = 0;

	foreach ($array as $arr) {
		$sumOfId += $arr[$id];
	}
	return $sumOfId;
}
// Sum of selected Index

// Functions for time calculation
function time_since($since)
{
	$chunks = array(
		array(60 * 60 * 24 * 365, 'year'),
		array(60 * 60 * 24 * 30, 'month'),
		array(60 * 60 * 24 * 7, 'week'),
		array(60 * 60 * 24, 'day'),
		array(60 * 60, 'hour'),
		array(60, 'minute'),
		array(1, 'second'),
	);

	for ($i = 0, $j = count($chunks); $i < $j; $i++) {
		$seconds = $chunks[$i][0];
		$name = $chunks[$i][1];
		if (($count = floor($since / $seconds)) != 0) {
			break;
		}
	}

	$print = ($count == 1) ? '1 ' . $name : "$count {$name}s";
	return $print;
}


function timeInSeconds($createdTime)
{
	$currentTime = time();
	$createdTimestamp = strtotime($createdTime);
	$timeDifference = $currentTime - $createdTimestamp;

	return $timeDifference;
}
// Functions for time calculation

// Calculate Remaining time 
function remainingTime($targetDate)
{
	// Convert target date to a DateTime object
	$targetDateTime = new DateTime($targetDate);

	// Get current date and time
	$currentDateTime = new DateTime();

	// Calculate the difference between the target and current dates
	$interval = $currentDateTime->diff($targetDateTime);

	// Get days, hours, minutes, and seconds from the interval
	$totalDays = $interval->days;
	$remainingHours = $interval->h;
	$remainingMinutes = $interval->i;

	return implode(
		", ",
		array(
			"totaldays" => $totalDays . " days",
			'remainingHours' => $remainingHours . " hours",
			'remainingMinutes' => $remainingMinutes . " mins"
		)
	);
}
// Calculate Remaining time 

// Phone Number formatter
function phone_no_format($input)
{
	$number = preg_replace('/[^0-9]/', '', $input); // Remove non-numeric characters
	$formattedNumber = '';

	if (strlen($number) > 0) {
		$formattedNumber .= '(' . substr($number, 0, 3) . ')' . ' ' . substr($number, 3, 3) . '-' . substr($number, 6, 4);
	}

	return $formattedNumber;
}
// Phone Number formatter

// CNIC Formatter
function cnic_format($number)
{
	$number = preg_replace('/[^0-9]/', '', $number); // Remove non-numeric characters

	if (strlen($number) >= 10) {
		$formattedNumber = substr($number, 0, 5) . '-' . substr($number, 5, 7) . '-' . substr($number, -1);
		return $formattedNumber;
	}

	return $number; // Return original number if not enough digits for formatting
}
// CNIC Formatter