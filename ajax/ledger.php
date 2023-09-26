<?php

session_start();
####################### Database Connection #######################
require("../auth/config.php");                                       #
require("../auth/functions.php");                                       #
$conn = conn("localhost", "root", "", "unitySync");                   #
$DB_Connection = new DB("localhost", "unitySync", "root", ""); #
$PDO_conn = $DB_Connection->getConnection(); #
####################### Database Connection #######################

include "../object/ledger.php";
$ledger_obj = new Ledger($PDO_conn);

$account = encryptor("decrypt", $_GET['account']);

$fetch_ledger = [
    'source' => $account,
    'pay_to' => $account,
    'project' => $_SESSION['project']
];
$ledger = $ledger_obj->fetch($fetch_ledger);

$query = "SELECT * FROM `accounts` WHERE `project_id` = '".$_SESSION['project']."';";
$all_accounts = fetch_Data($conn, $query);
foreach ($all_accounts as $key => $Acc) {
    $query = "SELECT `name`, `acc_id` FROM `".$Acc['type']."` WHERE `acc_id` = '".$Acc['acc_id']."' AND `project_id` = '".$_SESSION['project']."';";
    $acc = mysqli_fetch_assoc(mysqli_query($conn, $query));

    $accounts[$key] = $acc;
    $accounts[$key]['type'] = $Acc['type'];
}

sleep(1);

if (!empty($ledger)) {
    foreach ($ledger as $key => $led) {
        echo '<tr>
        <td class="fw-bolder">'.($key+1).'</td>
        <td>'.$led['v-id'].'</td>
        <td class="fw-bold text-center text-capitalize">';
        foreach ($accounts as $key => $account) {
            if ($led['source'] == $account['acc_id']) {
                echo $account['type']." - ".$account['name'];
            }
        }
        echo '</td>
        <td>'.substr($led['remarks'],0,54).' ...</td>';
        if (!empty($led['credit'])) {
            echo '<td class="text-end text-success">
            <svg class="icon icon-xs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
            Rs. '.number_format($led['credit']).'</td>';
        } else {
            echo '<td class="text-end text-danger">Rs. '.number_format($led['debit']).'
            <svg class="icon icon-xs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </td>';
        }
        if ($led['balance'] == 0) {
            echo '<td class="fw-bold text-end text-success">
            Rs. '.number_format($led['balance']).'
                <svg class="icon icon-xs me-1" viewBox="0 0 24 24" width="24" height="24"
                    stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"
                    stroke-linejoin="round" class="css-i6dzq1">
                    <circle cx="12" cy="12" r="4"></circle>
                    <line x1="1.05" y1="12" x2="7" y2="12"></line>
                    <line x1="17.01" y1="12" x2="22.96" y2="12"></line>
                </svg>
            </td>';
        } elseif ($led['balance'] > 0) {
            echo '<td class="fw-bold text-end text-success">
            Rs. '.number_format($led['balance']).'
                <svg class="icon icon-xs me-1" viewBox="0 0 24 24" width="24" height="24"
                    stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"
                    stroke-linejoin="round" class="css-i6dzq1">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                    <polyline points="17 6 23 6 23 12"></polyline>
                </svg>
            </td>';
        } else {
            echo '<td class="fw-bold text-end text-danger">
            Rs. '.number_format($led['balance']).'
                <svg class="icon icon-xs me-1" viewBox="0 0 24 24" width="24" height="24"
                    stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"
                    stroke-linejoin="round" class="css-i6dzq1">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </td>';
        }
        echo '</tr>';
    }
} else {
    echo "empty";
}
?>
