<?php

require_once('../tools/functions.php');
require_once('../classes/account.class.php');

$username = $first_name = $last_name = $password = $role = '';
$usernameErr = $first_nameErr = $last_nameErr = $passwordErr = $roleErr = '';

$accountObj = new Account();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean_input($_POST['username']);
    $first_name = clean_input($_POST['first_name']);
    $last_name = clean_input($_POST['last_name']);
    $password = clean_input($_POST['password']);
    $role = clean_input($_POST['role']);

    if (empty($username)) {
        $usernameErr = 'Username is required.';
    } else if ($accountObj->usernameExist($username, 0)) {
        $usernameErr = 'Username already exists.';
    }

    if (empty($first_name)) {
        $first_nameErr = 'First Name is required.';
    }

    if (empty($last_name)) {
        $last_nameErr = 'Last Name is required.';
    }

    if (empty($password)) {
        $passwordErr = 'Password is required.';
    }

    if (empty($role)) {
        $roleErr = 'Role is required.';
    }

    // Role must be either 'staff' or 'admin'
    if ($role != 'staff' && $role != 'admin') {
        $roleErr = 'Role must be either staff or admin.';
    }

    // If there are validation errors, return them as JSON
    if (!empty($usernameErr) || !empty($first_nameErr) || !empty($last_nameErr) || !empty($passwordErr) || !empty($roleErr)) {
        echo json_encode([
            'status' => 'error',
            'usernameErr' => $usernameErr,
            'first_nameErr' => $first_nameErr,
            'last_nameErr' => $last_nameErr,
            'passwordErr' => $passwordErr,
            'roleErr' => $roleErr
        ]);
        exit;
    }

    if (empty($usernameErr) && empty($first_nameErr) && empty($last_nameErr) && empty($passwordErr) && empty($roleErr)) {
        $accountObj->username = $username;
        $accountObj->first_name = $first_name;
        $accountObj->last_name = $last_name;
        $accountObj->password = $password;
        $accountObj->role = $role;
        $accountObj->is_staff = $role == 'staff' ? 1 : 0;
        $accountObj->is_admin = $role == 'admin' ? 1 : 0;

        if ($accountObj->add()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new account.']);
        }
        exit;
    }



    // $code = clean_input($_POST['code']);
    // $name = clean_input($_POST['name']);
    // $category = clean_input($_POST['category']);
    // $price = clean_input($_POST['price']);

    // if(empty($code)){
    //     $codeErr = 'Product Code is required.';
    // } else if ($productObj->codeExists($code)){
    //     $codeErr = 'Product Code already exists.';
    // }

    // if(empty($name)){
    //     $nameErr = 'Name is required.';
    // }

    // if(empty($category)){
    //     $categoryErr = 'Category is required.';
    // }

    // if(empty($price)){
    //     $priceErr = 'Price is required.';
    // } else if (!is_numeric($price)){
    //     $priceErr = 'Price should be a number.';
    // } else if ($price < 1){
    //     $priceErr = 'Price must be greater than 0.';
    // }

    // // If there are validation errors, return them as JSON
    // if(!empty($codeErr) || !empty($nameErr) || !empty($categoryErr) || !empty($priceErr)){
    //     echo json_encode([
    //         'status' => 'error',
    //         'codeErr' => $codeErr,
    //         'nameErr' => $nameErr,
    //         'categoryErr' => $categoryErr,
    //         'priceErr' => $priceErr
    //     ]);
    //     exit;
    // }

    // if(empty($codeErr) && empty($nameErr) && empty($categoryErr) && empty($priceErr)){
    //     $productObj->code = $code;
    //     $productObj->name = $name;
    //     $productObj->category_id = $category;
    //     $productObj->price = $price;

    //     if($productObj->add()){
    //         echo json_encode(['status' => 'success']);
    //     } else {
    //         echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new product.']);
    //     }
    //     exit;
    // }
}
